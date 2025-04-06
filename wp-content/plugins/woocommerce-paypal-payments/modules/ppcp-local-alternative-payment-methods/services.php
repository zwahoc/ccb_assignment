<?php

/**
 * The local alternative payment methods module services.
 *
 * @package WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods;

use WooCommerce\PayPalCommerce\Vendor\Psr\Container\ContainerInterface;
use WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\LocalApmProductStatus;
return array('ppcp-local-apms.url' => static function (ContainerInterface $container): string {
    /**
     * The path cannot be false.
     *
     * @psalm-suppress PossiblyFalseArgument
     */
    return plugins_url('/modules/ppcp-local-alternative-payment-methods/', dirname(realpath(__FILE__), 3) . '/woocommerce-paypal-payments.php');
}, 'ppcp-local-apms.payment-methods' => static function (ContainerInterface $container): array {
    return array('bancontact' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BancontactGateway::ID, 'countries' => array('BE'), 'currencies' => array('EUR')), 'blik' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BlikGateway::ID, 'countries' => array('PL'), 'currencies' => array('PLN')), 'eps' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\EPSGateway::ID, 'countries' => array('AT'), 'currencies' => array('EUR')), 'ideal' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\IDealGateway::ID, 'countries' => array('NL'), 'currencies' => array('EUR')), 'mybank' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MyBankGateway::ID, 'countries' => array('IT'), 'currencies' => array('EUR')), 'p24' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\P24Gateway::ID, 'countries' => array('PL'), 'currencies' => array('EUR', 'PLN')), 'trustly' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\TrustlyGateway::ID, 'countries' => array('AT', 'DE', 'DK', 'EE', 'ES', 'FI', 'GB', 'LT', 'LV', 'NL', 'NO', 'SE'), 'currencies' => array('EUR', 'DKK', 'SEK', 'GBP', 'NOK')), 'multibanco' => array('id' => \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MultibancoGateway::ID, 'countries' => array('PT'), 'currencies' => array('EUR')));
}, 'ppcp-local-apms.product-status' => static function (ContainerInterface $container): LocalApmProductStatus {
    return new LocalApmProductStatus($container->get('wcgateway.settings'), $container->get('api.endpoint.partners'), $container->get('settings.flag.is-connected'), $container->get('api.helper.failure-registry'));
}, 'ppcp-local-apms.bancontact.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BancontactGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BancontactGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.blik.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BlikGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BlikGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.eps.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\EPSGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\EPSGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.ideal.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\IDealGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\IDealGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.mybank.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MyBankGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MyBankGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.p24.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\P24Gateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\P24Gateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.trustly.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\TrustlyGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\TrustlyGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.multibanco.wc-gateway' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MultibancoGateway {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MultibancoGateway($container->get('api.endpoint.orders'), $container->get('api.factory.purchase-unit'), $container->get('wcgateway.processor.refunds'), $container->get('wcgateway.transaction-url-provider'));
}, 'ppcp-local-apms.bancontact.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BancontactPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BancontactPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.bancontact.wc-gateway'));
}, 'ppcp-local-apms.blik.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BlikPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\BlikPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.blik.wc-gateway'));
}, 'ppcp-local-apms.eps.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\EPSPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\EPSPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.eps.wc-gateway'));
}, 'ppcp-local-apms.ideal.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\IDealPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\IDealPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.ideal.wc-gateway'));
}, 'ppcp-local-apms.mybank.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MyBankPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MyBankPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.mybank.wc-gateway'));
}, 'ppcp-local-apms.p24.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\P24PaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\P24PaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.p24.wc-gateway'));
}, 'ppcp-local-apms.trustly.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\TrustlyPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\TrustlyPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.trustly.wc-gateway'));
}, 'ppcp-local-apms.multibanco.payment-method' => static function (ContainerInterface $container): \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MultibancoPaymentMethod {
    return new \WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods\MultibancoPaymentMethod($container->get('ppcp-local-apms.url'), $container->get('ppcp.asset-version'), $container->get('ppcp-local-apms.multibanco.wc-gateway'));
});
