<?php

/**
 * The Trustly payment gateway.
 *
 * @package WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\LocalAlternativePaymentMethods;

use WC_Payment_Gateway;
use WooCommerce\PayPalCommerce\ApiClient\Endpoint\Orders;
use WooCommerce\PayPalCommerce\ApiClient\Factory\PurchaseUnitFactory;
use WooCommerce\PayPalCommerce\Button\Exception\RuntimeException;
use WooCommerce\PayPalCommerce\WcGateway\Gateway\PayPalGateway;
use WooCommerce\PayPalCommerce\WcGateway\Gateway\TransactionUrlProvider;
use WooCommerce\PayPalCommerce\WcGateway\Processor\RefundProcessor;
/**
 * Class TrustlyGateway
 */
class TrustlyGateway extends WC_Payment_Gateway
{
    const ID = 'ppcp-trustly';
    /**
     * PayPal Orders endpoint.
     *
     * @var Orders
     */
    private $orders_endpoint;
    /**
     * Purchase unit factory.
     *
     * @var PurchaseUnitFactory
     */
    private $purchase_unit_factory;
    /**
     * The Refund Processor.
     *
     * @var RefundProcessor
     */
    private $refund_processor;
    /**
     * Service able to provide transaction url for an order.
     *
     * @var TransactionUrlProvider
     */
    protected $transaction_url_provider;
    /**
     * TrustlyGateway constructor.
     *
     * @param Orders                 $orders_endpoint PayPal Orders endpoint.
     * @param PurchaseUnitFactory    $purchase_unit_factory Purchase unit factory.
     * @param RefundProcessor        $refund_processor The Refund Processor.
     * @param TransactionUrlProvider $transaction_url_provider Service providing transaction view URL based on order.
     */
    public function __construct(Orders $orders_endpoint, PurchaseUnitFactory $purchase_unit_factory, RefundProcessor $refund_processor, TransactionUrlProvider $transaction_url_provider)
    {
        $this->id = self::ID;
        $this->supports = array('refunds', 'products');
        $this->method_title = __('Trustly (via PayPal)', 'woocommerce-paypal-payments');
        $this->method_description = __('A European payment method that allows buyers to make payments directly from their bank accounts, suitable for customers across multiple European countries. Supported currencies include EUR, DKK, SEK, GBP, and NOK.', 'woocommerce-paypal-payments');
        $this->title = $this->get_option('title', __('Trustly', 'woocommerce-paypal-payments'));
        $this->description = $this->get_option('description', '');
        $this->icon = esc_url('https://www.paypalobjects.com/images/checkout/alternative_payments/paypal_trustly_color.svg');
        $this->init_form_fields();
        $this->init_settings();
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        $this->orders_endpoint = $orders_endpoint;
        $this->purchase_unit_factory = $purchase_unit_factory;
        $this->refund_processor = $refund_processor;
        $this->transaction_url_provider = $transaction_url_provider;
    }
    /**
     * Initialize the form fields.
     */
    public function init_form_fields()
    {
        $this->form_fields = array('enabled' => array('title' => __('Enable/Disable', 'woocommerce-paypal-payments'), 'type' => 'checkbox', 'label' => __('Trustly', 'woocommerce-paypal-payments'), 'default' => 'no', 'desc_tip' => \true, 'description' => __('Enable/Disable Trustly payment gateway.', 'woocommerce-paypal-payments')), 'title' => array('title' => __('Title', 'woocommerce-paypal-payments'), 'type' => 'text', 'default' => $this->title, 'desc_tip' => \true, 'description' => __('This controls the title which the user sees during checkout.', 'woocommerce-paypal-payments')), 'description' => array('title' => __('Description', 'woocommerce-paypal-payments'), 'type' => 'text', 'default' => $this->description, 'desc_tip' => \true, 'description' => __('This controls the description which the user sees during checkout.', 'woocommerce-paypal-payments')));
    }
    /**
     * Processes the order.
     *
     * @param int $order_id The WC order ID.
     * @return array
     */
    public function process_payment($order_id)
    {
        $wc_order = wc_get_order($order_id);
        $wc_order->update_status('on-hold', __('Awaiting Trustly to confirm the payment.', 'woocommerce-paypal-payments'));
        $purchase_unit = $this->purchase_unit_factory->from_wc_order($wc_order);
        $amount = $purchase_unit->amount()->to_array();
        $request_body = array('intent' => 'CAPTURE', 'payment_source' => array('trustly' => array('country_code' => $wc_order->get_billing_country(), 'name' => $wc_order->get_billing_first_name() . ' ' . $wc_order->get_billing_last_name(), 'email' => $wc_order->get_billing_email())), 'processing_instruction' => 'ORDER_COMPLETE_ON_PAYMENT_APPROVAL', 'purchase_units' => array(array('reference_id' => $purchase_unit->reference_id(), 'amount' => array('currency_code' => $amount['currency_code'], 'value' => $amount['value']), 'custom_id' => $purchase_unit->custom_id(), 'invoice_id' => $purchase_unit->invoice_id())), 'application_context' => array('locale' => $this->valid_bcp47_code(), 'return_url' => $this->get_return_url($wc_order), 'cancel_url' => add_query_arg('cancelled', 'true', $this->get_return_url($wc_order))));
        try {
            $response = $this->orders_endpoint->create($request_body);
        } catch (RuntimeException $exception) {
            $wc_order->update_status('failed', $exception->getMessage());
            return array('result' => 'failure', 'redirect' => wc_get_checkout_url());
        }
        $body = json_decode($response['body']);
        $wc_order->update_meta_data(PayPalGateway::ORDER_ID_META_KEY, $body->id);
        $wc_order->save_meta_data();
        $payer_action = '';
        foreach ($body->links as $link) {
            if ($link->rel === 'payer-action') {
                $payer_action = $link->href;
            }
        }
        WC()->cart->empty_cart();
        return array('result' => 'success', 'redirect' => esc_url($payer_action));
    }
    /**
     * Process refund.
     *
     * If the gateway declares 'refunds' support, this will allow it to refund.
     * a passed in amount.
     *
     * @param  int    $order_id Order ID.
     * @param  float  $amount Refund amount.
     * @param  string $reason Refund reason.
     * @return boolean True or false based on success, or a WP_Error object.
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $order = wc_get_order($order_id);
        if (!is_a($order, \WC_Order::class)) {
            return \false;
        }
        return $this->refund_processor->process($order, (float) $amount, (string) $reason);
    }
    /**
     * Return transaction url for this gateway and given order.
     *
     * @param \WC_Order $order WC order to get transaction url by.
     *
     * @return string
     */
    public function get_transaction_url($order): string
    {
        $this->view_transaction_url = $this->transaction_url_provider->get_transaction_url_base($order);
        return parent::get_transaction_url($order);
    }
    /**
     * Returns a PayPal-supported BCP-47 code, for example de-DE-formal becomes de-DE.
     *
     * @return string
     */
    private function valid_bcp47_code()
    {
        $locale = str_replace('_', '-', get_user_locale());
        if (preg_match('/^[a-z]{2}(?:-[A-Z][a-z]{3})?(?:-(?:[A-Z]{2}))?$/', $locale)) {
            return $locale;
        }
        $parts = explode('-', $locale);
        if (count($parts) === 3) {
            $ret = substr($locale, 0, strrpos($locale, '-'));
            if (\false !== $ret) {
                return $ret;
            }
        }
        return 'en';
    }
}
