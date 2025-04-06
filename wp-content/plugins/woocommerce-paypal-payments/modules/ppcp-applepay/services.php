<?php

/**
 * The Applepay module services.
 *
 * @package WooCommerce\PayPalCommerce\Applepay
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\Applepay;

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodTypeInterface;
use WooCommerce\PayPalCommerce\ApiClient\Helper\Cache;
use WooCommerce\PayPalCommerce\Applepay\Assets\ApplePayButton;
use WooCommerce\PayPalCommerce\Applepay\Assets\AppleProductStatus;
use WooCommerce\PayPalCommerce\Applepay\Assets\DataToAppleButtonScripts;
use WooCommerce\PayPalCommerce\Applepay\Assets\BlocksPaymentMethod;
use WooCommerce\PayPalCommerce\Applepay\Assets\PropertiesDictionary;
use WooCommerce\PayPalCommerce\Applepay\Helper\ApmApplies;
use WooCommerce\PayPalCommerce\Applepay\Helper\AvailabilityNotice;
use WooCommerce\PayPalCommerce\Common\Pattern\SingletonDecorator;
use WooCommerce\PayPalCommerce\WcGateway\Helper\Environment;
use WooCommerce\PayPalCommerce\Vendor\Psr\Container\ContainerInterface;
return array(
    // @deprecated - use `applepay.eligibility.check` instead.
    'applepay.eligible' => static function (ContainerInterface $container): bool {
        $eligibility_check = $container->get('applepay.eligibility.check');
        return $eligibility_check();
    },
    'applepay.eligibility.check' => static function (ContainerInterface $container): callable {
        $apm_applies = $container->get('applepay.helpers.apm-applies');
        assert($apm_applies instanceof ApmApplies);
        return static function () use ($apm_applies): bool {
            return $apm_applies->for_country() && $apm_applies->for_currency() && $apm_applies->for_merchant();
        };
    },
    'applepay.helpers.apm-applies' => static function (ContainerInterface $container): ApmApplies {
        return new ApmApplies($container->get('applepay.supported-countries'), $container->get('applepay.supported-currencies'), $container->get('api.shop.currency.getter'), $container->get('api.shop.country'));
    },
    'applepay.status-cache' => static function (ContainerInterface $container): Cache {
        return new Cache('ppcp-paypal-apple-status-cache');
    },
    // We assume it's a referral if we can check product status without API request failures.
    'applepay.is_referral' => static function (ContainerInterface $container): bool {
        $status = $container->get('applepay.apple-product-status');
        assert($status instanceof AppleProductStatus);
        return !$status->has_request_failure();
    },
    'applepay.availability_notice' => static function (ContainerInterface $container): AvailabilityNotice {
        $settings = $container->get('wcgateway.settings');
        return new AvailabilityNotice($container->get('applepay.apple-product-status'), $container->get('wcgateway.is-wc-gateways-list-page'), $container->get('wcgateway.is-ppcp-settings-page'), $container->get('applepay.available') || !$container->get('applepay.is_referral'), $container->get('applepay.server_supported'), $container->get('applepay.is_validated'), $container->get('applepay.button'));
    },
    'applepay.has_validated' => static function (ContainerInterface $container): bool {
        $settings = $container->get('wcgateway.settings');
        return $settings->has('applepay_validated');
    },
    'applepay.is_validated' => static function (ContainerInterface $container): bool {
        $settings = $container->get('wcgateway.settings');
        return $settings->has('applepay_validated') ? $settings->get('applepay_validated') === \true : \false;
    },
    'applepay.apple-product-status' => SingletonDecorator::make(static function (ContainerInterface $container): AppleProductStatus {
        return new AppleProductStatus($container->get('wcgateway.settings'), $container->get('api.endpoint.partners'), $container->get('settings.flag.is-connected'), $container->get('api.helper.failure-registry'));
    }),
    'applepay.available' => static function (ContainerInterface $container): bool {
        if (apply_filters('woocommerce_paypal_payments_applepay_validate_product_status', \true)) {
            $status = $container->get('applepay.apple-product-status');
            assert($status instanceof AppleProductStatus);
            /**
             * If merchant isn't onboarded via /v1/customer/partner-referrals this returns false as the API call fails.
             */
            return apply_filters('woocommerce_paypal_payments_applepay_product_status', $status->is_active());
        }
        return \true;
    },
    'applepay.server_supported' => static function (ContainerInterface $container): bool {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    },
    'applepay.is_browser_supported' => static function (ContainerInterface $container): bool {
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $user_agent = wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? '');
        if ($user_agent) {
            foreach (PropertiesDictionary::DISALLOWED_USER_AGENTS as $disallowed_agent) {
                if (strpos($user_agent, $disallowed_agent) !== \false) {
                    return \false;
                }
            }
            $browser_allowed = \false;
            foreach (PropertiesDictionary::ALLOWED_USER_BROWSERS as $allowed_browser) {
                if (strpos($user_agent, $allowed_browser) !== \false) {
                    $browser_allowed = \true;
                    break;
                }
            }
            $device_allowed = \false;
            foreach (PropertiesDictionary::ALLOWED_USER_DEVICES as $allowed_devices) {
                if (strpos($user_agent, $allowed_devices) !== \false) {
                    $device_allowed = \true;
                    break;
                }
            }
            return $browser_allowed && $device_allowed;
        }
        return \false;
    },
    'applepay.url' => static function (ContainerInterface $container): string {
        $path = realpath(__FILE__);
        if (\false === $path) {
            return '';
        }
        return plugins_url('/modules/ppcp-applepay/', dirname($path, 3) . '/woocommerce-paypal-payments.php');
    },
    'applepay.sdk_script_url' => static function (ContainerInterface $container): string {
        return 'https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js';
    },
    'applepay.data_to_scripts' => static function (ContainerInterface $container): DataToAppleButtonScripts {
        return new DataToAppleButtonScripts($container->get('applepay.sdk_script_url'), $container->get('wcgateway.settings'));
    },
    'applepay.button' => static function (ContainerInterface $container): ApplePayButton {
        return new ApplePayButton($container->get('wcgateway.settings'), $container->get('woocommerce.logger.woocommerce'), $container->get('wcgateway.order-processor'), $container->get('applepay.url'), $container->get('ppcp.asset-version'), $container->get('applepay.data_to_scripts'), $container->get('wcgateway.settings.status'), $container->get('button.helper.cart-products'));
    },
    'applepay.blocks-payment-method' => static function (ContainerInterface $container): PaymentMethodTypeInterface {
        return new BlocksPaymentMethod('ppcp-applepay', $container->get('applepay.url'), $container->get('ppcp.asset-version'), $container->get('applepay.button'), $container->get('blocks.method'));
    },
    /**
     * The list of which countries can be used for ApplePay.
     */
    'applepay.supported-countries' => static function (ContainerInterface $container): array {
        /**
         * Returns which countries can be used for ApplePay.
         */
        return apply_filters(
            'woocommerce_paypal_payments_applepay_supported_countries',
            // phpcs:disable Squiz.Commenting.InlineComment
            array(
                'AU',
                // Australia
                'AT',
                // Austria
                'BE',
                // Belgium
                'BG',
                // Bulgaria
                'CA',
                // Canada
                'CN',
                // China
                'CY',
                // Cyprus
                'CZ',
                // Czech Republic
                'DK',
                // Denmark
                'EE',
                // Estonia
                'FI',
                // Finland
                'FR',
                // France
                'DE',
                // Germany
                'GR',
                // Greece
                'HK',
                // Hong Kong
                'HU',
                // Hungary
                'IE',
                // Ireland
                'IT',
                // Italy
                'LV',
                // Latvia
                'LI',
                // Liechtenstein
                'LT',
                // Lithuania
                'LU',
                // Luxembourg
                'MT',
                // Malta
                'NL',
                // Netherlands
                'NO',
                // Norway
                'PL',
                // Poland
                'PT',
                // Portugal
                'RO',
                // Romania
                'SG',
                // Singapore
                'SK',
                // Slovakia
                'SI',
                // Slovenia
                'ES',
                // Spain
                'SE',
                // Sweden
                'US',
                // United States
                'GB',
            )
        );
    },
    /**
     * The list of which currencies can be used for ApplePay.
     */
    'applepay.supported-currencies' => static function (ContainerInterface $container): array {
        /**
         * Returns which currencies can be used for ApplePay.
         */
        return apply_filters(
            'woocommerce_paypal_payments_applepay_supported_currencies',
            // phpcs:disable Squiz.Commenting.InlineComment
            array(
                'AUD',
                // Australian Dollar
                'BRL',
                // Brazilian Real
                'CAD',
                // Canadian Dollar
                'CHF',
                // Swiss Franc
                'CZK',
                // Czech Koruna
                'DKK',
                // Danish Krone
                'EUR',
                // Euro
                'HKD',
                // Hong Kong Dollar
                'GBP',
                // British Pound Sterling
                'HUF',
                // Hungarian Forint
                'ILS',
                // Israeli New Shekel
                'JPY',
                // Japanese Yen
                'MXN',
                // Mexican Peso
                'NOK',
                // Norwegian Krone
                'NZD',
                // New Zealand Dollar
                'PHP',
                // Philippine Peso
                'PLN',
                // Polish Zloty
                'SGD',
                // Singapur-Dollar
                'SEK',
                // Swedish Krona
                'THB',
                // Thai Baht
                'TWD',
                // New Taiwan Dollar
                'USD',
            )
        );
    },
    'applepay.enable-url-sandbox' => static function (ContainerInterface $container): string {
        return 'https://www.sandbox.paypal.com/bizsignup/add-product?product=payment_methods&capabilities=APPLE_PAY';
    },
    'applepay.enable-url-live' => static function (ContainerInterface $container): string {
        return 'https://www.paypal.com/bizsignup/add-product?product=payment_methods&capabilities=APPLE_PAY';
    },
    'applepay.settings.connection.status-text' => static function (ContainerInterface $container): string {
        $is_connected = $container->get('settings.flag.is-connected');
        if (!$is_connected) {
            return '';
        }
        $product_status = $container->get('applepay.apple-product-status');
        assert($product_status instanceof AppleProductStatus);
        $environment = $container->get('settings.environment');
        assert($environment instanceof Environment);
        $enabled = $product_status->is_active();
        $enabled_status_text = esc_html__('Status: Available', 'woocommerce-paypal-payments');
        $disabled_status_text = esc_html__('Status: Not yet enabled', 'woocommerce-paypal-payments');
        $button_text = $enabled ? esc_html__('Settings', 'woocommerce-paypal-payments') : esc_html__('Enable Apple Pay', 'woocommerce-paypal-payments');
        $enable_url = $environment->current_environment_is(Environment::PRODUCTION) ? $container->get('applepay.enable-url-live') : $container->get('applepay.enable-url-sandbox');
        $button_url = $enabled ? admin_url('admin.php?page=wc-settings&tab=checkout&section=ppcp-gateway&ppcp-tab=ppcp-credit-card-gateway#ppcp-applepay_button_enabled') : $enable_url;
        return sprintf('<p>%1$s %2$s</p><p><a target="%3$s" href="%4$s" class="button">%5$s</a></p>', $enabled ? $enabled_status_text : $disabled_status_text, $enabled ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>', $enabled ? '_self' : '_blank', esc_url($button_url), esc_html($button_text));
    },
    'applepay.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\Applepay\ApplePayGateway {
        return new \WooCommerce\PayPalCommerce\Applepay\ApplePayGateway($container->get('wcgateway.order-processor'), $container->get('api.factory.paypal-checkout-url'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'), $container->get('session.handler'), $container->get('applepay.url'), $container->get('woocommerce.logger.woocommerce'));
    },
);
