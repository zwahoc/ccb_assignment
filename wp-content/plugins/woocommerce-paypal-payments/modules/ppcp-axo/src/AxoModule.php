<?php

/**
 * The Axo module.
 *
 * @package WooCommerce\PayPalCommerce\Axo
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\Axo;

use WooCommerce\PayPalCommerce\Vendor\Psr\Log\LoggerInterface;
use WooCommerce\PayPalCommerce\ApiClient\Authentication\SdkClientToken;
use WooCommerce\PayPalCommerce\ApiClient\Exception\PayPalApiException;
use WooCommerce\PayPalCommerce\ApiClient\Exception\RuntimeException;
use WooCommerce\PayPalCommerce\Axo\Assets\AxoManager;
use WooCommerce\PayPalCommerce\Axo\Gateway\AxoGateway;
use WooCommerce\PayPalCommerce\Button\Assets\SmartButtonInterface;
use WooCommerce\PayPalCommerce\Button\Helper\ContextTrait;
use WooCommerce\PayPalCommerce\Onboarding\Render\OnboardingOptionsRenderer;
use WooCommerce\PayPalCommerce\Session\SessionHandler;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ExecutableModule;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ExtendingModule;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ServiceModule;
use WooCommerce\PayPalCommerce\Vendor\Psr\Container\ContainerInterface;
use WooCommerce\PayPalCommerce\WcGateway\Gateway\CreditCardGateway;
use WooCommerce\PayPalCommerce\WcGateway\Settings\Settings;
use WooCommerce\PayPalCommerce\WcGateway\Helper\CartCheckoutDetector;
use WooCommerce\PayPalCommerce\WcGateway\Settings\SettingsListener;
use WooCommerce\PayPalCommerce\WcSubscriptions\Helper\SubscriptionHelper;
use WC_Payment_Gateways;
use WooCommerce\PayPalCommerce\WcGateway\Helper\CardPaymentsConfiguration;
/**
 * Class AxoModule
 *
 * @psalm-suppress MissingConstructor
 */
class AxoModule implements ServiceModule, ExtendingModule, ExecutableModule
{
    use ModuleClassNameIdTrait;
    use ContextTrait;
    /**
     * The session handler for ContextTrait.
     *
     * @var SessionHandler|null
     */
    protected ?SessionHandler $session_handler;
    /**
     * {@inheritDoc}
     */
    public function services(): array
    {
        return require __DIR__ . '/../services.php';
    }
    /**
     * {@inheritDoc}
     */
    public function extensions(): array
    {
        return require __DIR__ . '/../extensions.php';
    }
    /**
     * {@inheritDoc}
     */
    public function run(ContainerInterface $c): bool
    {
        add_filter(
            'woocommerce_payment_gateways',
            /**
             * Param types removed to avoid third-party issues.
             *
             * @psalm-suppress MissingClosureParamType
             */
            function ($methods) use ($c): array {
                if (!is_array($methods)) {
                    return $methods;
                }
                $gateway = $c->get('axo.gateway');
                // Check if the module is applicable, correct country, currency, ... etc.
                if (!$c->get('axo.eligible')) {
                    return $methods;
                }
                // Add the gateway in admin area.
                if (is_admin()) {
                    if (!$this->is_wc_settings_payments_tab()) {
                        $methods[] = $gateway;
                    }
                    return $methods;
                }
                if (is_user_logged_in()) {
                    return $methods;
                }
                $dcc_configuration = $c->get('wcgateway.configuration.card-configuration');
                assert($dcc_configuration instanceof CardPaymentsConfiguration);
                if (!$dcc_configuration->is_enabled()) {
                    return $methods;
                }
                if ($this->is_excluded_endpoint()) {
                    return $methods;
                }
                $methods[] = $gateway;
                return $methods;
            },
            1,
            9
        );
        // Hides credit card gateway on checkout when using Fastlane.
        add_filter(
            'woocommerce_available_payment_gateways',
            /**
             * Param types removed to avoid third-party issues.
             *
             * @psalm-suppress MissingClosureParamType
             */
            function ($methods) use ($c): array {
                if (!is_array($methods) || !$c->get('axo.eligible')) {
                    return $methods;
                }
                if (apply_filters('woocommerce_paypal_payments_axo_hide_credit_card_gateway', $this->hide_credit_card_when_using_fastlane($methods, $c))) {
                    unset($methods[CreditCardGateway::ID]);
                }
                return $methods;
            }
        );
        // Enforce Fastlane to always be the first payment method in the list.
        add_action('wc_payment_gateways_initialized', function (WC_Payment_Gateways $gateways) {
            if (is_admin()) {
                return;
            }
            foreach ($gateways->payment_gateways as $key => $gateway) {
                if ($gateway->id === AxoGateway::ID) {
                    unset($gateways->payment_gateways[$key]);
                    array_unshift($gateways->payment_gateways, $gateway);
                    break;
                }
            }
        });
        // Force 'cart-block' and 'cart' Smart Button locations in the settings.
        add_action('admin_init', static function () use ($c) {
            $listener = $c->get('wcgateway.settings.listener');
            assert($listener instanceof SettingsListener);
            $dcc_configuration = $c->get('wcgateway.configuration.card-configuration');
            assert($dcc_configuration instanceof CardPaymentsConfiguration);
            $listener->filter_settings($dcc_configuration->use_fastlane(), 'smart_button_locations', function (array $existing_setting_value) {
                $axo_forced_locations = array('cart-block', 'cart');
                return array_unique(array_merge($existing_setting_value, $axo_forced_locations));
            });
        });
        add_action('wp_loaded', function () use ($c) {
            $module = $this;
            $this->session_handler = $c->get('session.handler');
            $settings = $c->get('wcgateway.settings');
            assert($settings instanceof Settings);
            $is_paypal_enabled = $settings->has('enabled') && $settings->get('enabled') ?? \false;
            $subscription_helper = $c->get('wc-subscriptions.helper');
            assert($subscription_helper instanceof SubscriptionHelper);
            // Check if the module is applicable, correct country, currency, ... etc.
            if (!$is_paypal_enabled || !$c->get('axo.eligible') || $this->is_paypal_continuation() || $subscription_helper->cart_contains_subscription()) {
                return;
            }
            $manager = $c->get('axo.manager');
            assert($manager instanceof AxoManager);
            // Enqueue frontend scripts.
            add_action('wp_enqueue_scripts', static function () use ($c, $manager, $module) {
                $smart_button = $c->get('button.smart-button');
                assert($smart_button instanceof SmartButtonInterface);
                if ($module->should_render_fastlane($c) && $smart_button->should_load_ppcp_script()) {
                    $manager->enqueue();
                }
            });
            // Render submit button.
            add_action($manager->checkout_button_renderer_hook(), static function () use ($c, $manager, $module) {
                if ($module->should_render_fastlane($c)) {
                    $manager->render_checkout_button();
                }
            });
            /**
             * Late loading locations because of trouble with some shipping plugins
             */
            add_filter('woocommerce_paypal_payments_axo_shipping_wc_enabled_locations', function (array $locations) use ($c): array {
                return array_merge($locations, $c->get('axo.shipping-wc-enabled-locations'));
            });
            /**
             * Param types removed to avoid third-party issues.
             *
             * @psalm-suppress MissingClosureParamType
             */
            add_filter('woocommerce_paypal_payments_sdk_components_hook', function ($components) use ($c) {
                $dcc_configuration = $c->get('wcgateway.configuration.card-configuration');
                assert($dcc_configuration instanceof CardPaymentsConfiguration);
                if (!$dcc_configuration->use_fastlane()) {
                    return $components;
                }
                $components[] = 'fastlane';
                return $components;
            });
            add_action('wp_head', function () use ($c) {
                // Add meta tag to allow feature-detection of the site's AXO payment state.
                $dcc_configuration = $c->get('wcgateway.configuration.card-configuration');
                assert($dcc_configuration instanceof CardPaymentsConfiguration);
                if ($dcc_configuration->use_fastlane()) {
                    // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
                    echo '<script async src="https://www.paypalobjects.com/insights/v1/paypal-insights.sandbox.min.js"></script>';
                    $this->add_feature_detection_tag(\true);
                } else {
                    $this->add_feature_detection_tag(\false);
                }
            });
            add_filter('woocommerce_paypal_payments_localized_script_data', function (array $localized_script_data) use ($c, $module) {
                $api = $c->get('api.sdk-client-token');
                assert($api instanceof SdkClientToken);
                $logger = $c->get('woocommerce.logger.woocommerce');
                assert($logger instanceof LoggerInterface);
                return $module->add_sdk_client_token_to_script_data($api, $logger, $localized_script_data);
            });
            add_filter(
                'ppcp_onboarding_dcc_table_rows',
                /**
                 * Param types removed to avoid third-party issues.
                 *
                 * @psalm-suppress MissingClosureParamType
                 */
                function ($rows, $renderer): array {
                    if (!is_array($rows)) {
                        return $rows;
                    }
                    if ($renderer instanceof OnboardingOptionsRenderer) {
                        $rows[] = $renderer->render_table_row(__('Fastlane by PayPal', 'woocommerce-paypal-payments'), __('Yes', 'woocommerce-paypal-payments'), __('Help accelerate guest checkout with PayPal\'s autofill solution.', 'woocommerce-paypal-payments'));
                    }
                    return $rows;
                },
                10,
                2
            );
            // Set Axo as the default payment method on checkout for guest customers.
            add_action('template_redirect', function () use ($c) {
                if ($this->should_render_fastlane($c)) {
                    WC()->session->set('chosen_payment_method', AxoGateway::ID);
                }
            });
            // Add the markup necessary for displaying overlays and loaders for Axo on the checkout page.
            $this->add_checkout_loader_markup($c);
        }, 1);
        add_action('wc_ajax_' . \WooCommerce\PayPalCommerce\Axo\FrontendLoggerEndpoint::ENDPOINT, static function () use ($c) {
            $endpoint = $c->get('axo.endpoint.frontend-logger');
            assert($endpoint instanceof \WooCommerce\PayPalCommerce\Axo\FrontendLoggerEndpoint);
            $endpoint->handle_request();
        });
        // Enqueue the PayPal Insights script.
        add_action('wp_enqueue_scripts', function () use ($c) {
            $this->enqueue_paypal_insights_script_on_order_received($c);
        });
        return \true;
    }
    /**
     * Adds id token to localized script data.
     *
     * @param SdkClientToken  $api User id token api.
     * @param LoggerInterface $logger The logger.
     * @param array           $localized_script_data The localized script data.
     * @return array
     */
    private function add_sdk_client_token_to_script_data(SdkClientToken $api, LoggerInterface $logger, array $localized_script_data): array
    {
        try {
            $sdk_client_token = $api->sdk_client_token();
            $localized_script_data['axo'] = array('sdk_client_token' => $sdk_client_token);
        } catch (RuntimeException $exception) {
            $error = $exception->getMessage();
            if (is_a($exception, PayPalApiException::class)) {
                $error = $exception->get_details($error);
            }
            $logger->error($error);
        }
        return $localized_script_data;
    }
    /**
     * Condition to evaluate if Credit Card gateway should be hidden.
     *
     * @param array              $methods WC payment methods.
     * @param ContainerInterface $c The container.
     * @return bool
     */
    private function hide_credit_card_when_using_fastlane(array $methods, ContainerInterface $c): bool
    {
        return $this->should_render_fastlane($c) && isset($methods[CreditCardGateway::ID]);
    }
    /**
     * Condition to evaluate if Fastlane should be rendered.
     *
     * Fastlane should only render on the classic checkout, when Fastlane is enabled in the settings and also only for guest customers.
     *
     * @param ContainerInterface $c The container.
     * @return bool
     */
    private function should_render_fastlane(ContainerInterface $c): bool
    {
        $dcc_configuration = $c->get('wcgateway.configuration.card-configuration');
        assert($dcc_configuration instanceof CardPaymentsConfiguration);
        $subscription_helper = $c->get('wc-subscriptions.helper');
        assert($subscription_helper instanceof SubscriptionHelper);
        return !is_user_logged_in() && CartCheckoutDetector::has_classic_checkout() && $dcc_configuration->use_fastlane() && !$this->is_excluded_endpoint() && is_checkout() && !$subscription_helper->cart_contains_subscription();
    }
    /**
     * Adds the markup necessary for displaying overlays and loaders for Axo on the checkout page.
     *
     * @param ContainerInterface $c The container.
     * @return void
     */
    private function add_checkout_loader_markup(ContainerInterface $c): void
    {
        if ($this->should_render_fastlane($c)) {
            add_action('woocommerce_checkout_before_customer_details', function () {
                echo '<div class="ppcp-axo-loading">';
            });
            add_action('woocommerce_checkout_after_customer_details', function () {
                echo '</div>';
            });
            add_action('woocommerce_checkout_billing', function () {
                echo '<div class="loader"><div class="ppcp-axo-overlay"></div>';
            }, 8);
            add_action('woocommerce_checkout_billing', function () {
                echo '</div>';
            }, 12);
        }
    }
    /**
     * Condition to evaluate if the current endpoint is excluded.
     *
     * @return bool
     */
    private function is_excluded_endpoint(): bool
    {
        // Exclude the Order Pay and Order Received endpoints.
        return is_wc_endpoint_url('order-pay') || is_wc_endpoint_url('order-received');
    }
    /**
     * Outputs a meta tag to allow feature detection on certain pages.
     *
     * @param bool $axo_enabled Whether the gateway is enabled.
     * @return void
     */
    private function add_feature_detection_tag(bool $axo_enabled)
    {
        $show_tag = is_home() || is_checkout() || is_cart() || is_shop();
        if (!$show_tag) {
            return;
        }
        printf('<meta name="ppcp.axo" content="ppcp.axo.%s" />', $axo_enabled ? 'enabled' : 'disabled');
    }
    /**
     * Enqueues PayPal Insights on the Order Received endpoint.
     *
     * @param ContainerInterface $c The service container.
     * @return void
     */
    private function enqueue_paypal_insights_script_on_order_received(ContainerInterface $c): void
    {
        global $wp;
        if (!isset($wp->query_vars['order-received'])) {
            return;
        }
        $order_id = absint($wp->query_vars['order-received']);
        if (!$order_id) {
            return;
        }
        $order = wc_get_order($order_id);
        if (!$order || !$order instanceof \WC_Order) {
            return;
        }
        $module_url = $c->get('axo.url');
        $asset_version = $c->get('ppcp.asset-version');
        $insights_data = $c->get('axo.insights');
        wp_register_script('wc-ppcp-paypal-insights-end-checkout', untrailingslashit($module_url) . '/assets/js/TrackEndCheckout.js', array('wp-plugins', 'wp-data', 'wp-element', 'wc-blocks-registry'), $asset_version, \true);
        wp_localize_script('wc-ppcp-paypal-insights-end-checkout', 'wc_ppcp_axo_insights_data', array_merge($insights_data, array('orderId' => $order_id, 'orderTotal' => (string) $order->get_total(), 'orderCurrency' => (string) $order->get_currency(), 'paymentMethod' => (string) $order->get_payment_method(), 'orderKey' => (string) $order->get_order_key())));
        wp_enqueue_script('wc-ppcp-paypal-insights-end-checkout');
    }
}
