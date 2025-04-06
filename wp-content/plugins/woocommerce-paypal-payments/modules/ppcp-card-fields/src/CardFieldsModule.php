<?php

/**
 * The Card Fields module.
 *
 * @package WooCommerce\PayPalCommerce\CardFields
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\CardFields;

use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ExecutableModule;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ExtendingModule;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ModuleClassNameIdTrait;
use WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\ServiceModule;
use WooCommerce\PayPalCommerce\Vendor\Psr\Container\ContainerInterface;
use WooCommerce\PayPalCommerce\WcGateway\Gateway\CreditCardGateway;
use WooCommerce\PayPalCommerce\WcGateway\Settings\Settings;
use WooCommerce\PayPalCommerce\WcGateway\Helper\CardPaymentsConfiguration;
/**
 * Class CardFieldsModule
 */
class CardFieldsModule implements ServiceModule, ExtendingModule, ExecutableModule
{
    use ModuleClassNameIdTrait;
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
        if (!$c->get('card-fields.eligible')) {
            return \true;
        }
        add_filter('woocommerce_paypal_payments_sdk_components_hook', static function (array $components) use ($c) {
            $dcc_config = $c->get('wcgateway.configuration.card-configuration');
            assert($dcc_config instanceof CardPaymentsConfiguration);
            if (!$dcc_config->is_acdc_enabled()) {
                return $components;
            }
            // Enable the new "card-fields" component.
            $components[] = 'card-fields';
            // Ensure the older "hosted-fields" component is not loaded.
            return array_filter($components, static fn(string $component) => $component !== 'hosted-fields');
        });
        add_filter('woocommerce_paypal_payments_sdk_disabled_funding_hook', static function (array $disable_funding, array $flags) use ($c) {
            if (\true === $flags['is_block_context']) {
                return $disable_funding;
            }
            $dcc_config = $c->get('wcgateway.configuration.card-configuration');
            assert($dcc_config instanceof CardPaymentsConfiguration);
            if (!$dcc_config->is_acdc_enabled()) {
                return $disable_funding;
            }
            // For ACDC payments we need the funding source "card"!
            return array_filter($disable_funding, static fn(string $funding_source) => $funding_source !== 'card');
        }, 10, 2);
        add_filter(
            'woocommerce_credit_card_form_fields',
            /**
             * Return/Param types removed to avoid third-party issues.
             *
             * @psalm-suppress MissingClosureReturnType
             * @psalm-suppress MissingClosureParamType
             */
            function ($default_fields, $id) use ($c) {
                if (!$c->get('wcgateway.configuration.card-configuration')->is_enabled()) {
                    return $default_fields;
                }
                if (CreditCardGateway::ID === $id && apply_filters('woocommerce_paypal_payments_enable_cardholder_name_field', \false)) {
                    $default_fields['card-name-field'] = '<p class="form-row form-row-wide">
						<label for="ppcp-credit-card-gateway-card-name">' . esc_attr__('Cardholder Name', 'woocommerce-paypal-payments') . '</label>
						<input id="ppcp-credit-card-gateway-card-name" class="input-text wc-credit-card-form-card-expiry" type="text" placeholder="' . esc_attr__('Cardholder Name (optional)', 'woocommerce-paypal-payments') . '" name="ppcp-credit-card-gateway-card-name">
					</p>';
                    // Moves new item to first position.
                    $new_field = $default_fields['card-name-field'];
                    unset($default_fields['card-name-field']);
                    array_unshift($default_fields, $new_field);
                }
                if (apply_filters('woocommerce_paypal_payments_card_fields_translate_card_number', \true)) {
                    if (isset($default_fields['card-number-field'])) {
                        // Replaces the default card number placeholder with a translatable one.
                        $default_fields['card-number-field'] = str_replace('&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;', esc_attr__('Card number', 'woocommerce-paypal-payments'), $default_fields['card-number-field']);
                    }
                }
                return $default_fields;
            },
            10,
            2
        );
        add_filter('ppcp_create_order_request_body_data', function (array $data, string $payment_method) use ($c): array {
            if (!$c->get('wcgateway.configuration.card-configuration')->is_enabled()) {
                return $data;
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            if ($payment_method !== CreditCardGateway::ID) {
                return $data;
            }
            $settings = $c->get('wcgateway.settings');
            assert($settings instanceof Settings);
            $three_d_secure_contingency = $settings->has('3d_secure_contingency') ? apply_filters('woocommerce_paypal_payments_three_d_secure_contingency', $settings->get('3d_secure_contingency')) : '';
            if ($three_d_secure_contingency === 'SCA_ALWAYS' || $three_d_secure_contingency === 'SCA_WHEN_REQUIRED') {
                $data['payment_source']['card'] = array('attributes' => array('verification' => array('method' => $three_d_secure_contingency)));
            }
            return $data;
        }, 10, 2);
        return \true;
    }
}
