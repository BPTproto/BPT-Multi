<?php

namespace BPT\pay;

use BPT\pay\crypto\invoicePaymentInterface;
use BPT\pay\crypto\estimatePriceInterface;
use BPT\pay\crypto\estimateUpdateInterface;
use BPT\pay\crypto\invoiceResponseInterface;
use BPT\pay\crypto\ipnDataInterface;
use BPT\pay\crypto\paymentInterface;
use BPT\settings;
use BPT\tools;
use CurlHandle;

class crypto {
    private static string $api_key = '';

    private static string $ipn_secret = '';

    const API_BASE = 'https://api.nowpayments.io/v1/';

    private static CurlHandle $session;

    public static function init (string $api_key = '', string $ipn_secret = ''): void {
        self::$api_key = settings::$pay['crypto']['api_key'] ?? $api_key;
        self::$ipn_secret = settings::$pay['crypto']['ipn_secret'] ?? $ipn_secret;
        self::$session = curl_init();
        curl_setopt(self::$session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt(self::$session, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . self::$api_key,
            'Content-Type: application/json'
        ]);
    }

    private static function execute (string $method, string $endpoint, string|array $data = '') {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (empty($value)) {
                    unset($data[$key]);
                }
            }
        }

        $session = self::$session;

        switch ($method) {
            case 'GET':
                curl_setopt($session, CURLOPT_URL, self::API_BASE . $endpoint . !empty($data) && is_array($data) ? ('?' . http_build_query($data)) : '');
                break;
            case 'POST':
                curl_setopt($session, CURLOPT_POST, true);
                curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($session, CURLOPT_URL, self::API_BASE . $endpoint);
                break;
            default:
                return false;
        }
        return json_decode(curl_exec($session));
    }

    public static function status (): bool {
        return self::execute('GET', 'status')->message === 'OK';
    }

    /**
     * @return estimatePriceInterface|mixed
     */
    public static function getEstimatePrice (int|float $amount, string $currency_from, string $currency_to) {
        return self::execute('GET', 'estimate', [
            'amount'        => $amount,
            'currency_from' => $currency_from,
            'currency_to'   => $currency_to
        ]);
    }

    /**
     * @return invoicePaymentInterface|mixed
     */
    public static function createPayment (int|float $price_amount, string $price_currency, string $pay_currency, int|float $pay_amount = null, string $ipn_callback_url = null, string $order_id = null, string $order_description = null, string $purchase_id = null, string $payout_address = null, string $payout_currency = null, string $payout_extra_id = null, bool $fixed_rate = null) {
        return self::execute('POST', 'payment', [
            'price_amount'      => $price_amount,
            'price_currency'    => $price_currency,
            'pay_currency'      => $pay_currency,
            'pay_amount'        => $pay_amount,
            'ipn_callback_url'  => $ipn_callback_url,
            'order_id'          => $order_id,
            'order_description' => $order_description,
            'purchase_id'       => $purchase_id,
            'payout_address'    => $payout_address,
            'payout_currency'   => $payout_currency,
            'payout_extra_id'   => $payout_extra_id,
            'fixed_rate'        => $fixed_rate
        ]);
    }

    /**
     * @return invoicePaymentInterface|mixed
     */
    public static function createInvoicePayment (string $iid, string $pay_currency, string $purchase_id = null, string $order_description = null, string $customer_email = null, string $payout_address = null, string $payout_extra_id = null, string $payout_currency = null) {
        return self::execute('POST', 'invoice', [
            'iid'               => $iid,
            'pay_currency'      => $pay_currency,
            'purchase_id'       => $purchase_id,
            'order_description' => $order_description,
            'customer_email'    => $customer_email,
            'payout_address'    => $payout_address,
            'payout_extra_id'   => $payout_extra_id,
            'payout_currency'   => $payout_currency
        ]);
    }

    /**
     * @return estimateUpdateInterface|mixed
     */
    public static function updateEstimatePrice (int $paymentID) {
        return self::execute('POST', 'payment/' . $paymentID . '/update-merchant-estimate');
    }

    /**
     * @return paymentInterface|mixed
     */
    public static function getPaymentStatus (int $paymentID) {
        return self::execute('GET', 'payment/' . $paymentID);
    }

    public static function getMinimumPaymentAmount (string $currency_from, string $currency_to): float {
        return self::execute('GET', 'min-amount', [
            'currency_from' => $currency_from,
            'currency_to'   => $currency_to
        ])->min_amount;
    }

    /**
     * @return invoiceResponseInterface|mixed
     */
    public static function createInvoice (int|float $price_amount, string $price_currency, string $pay_currency, int|float $pay_amount = null, string $ipn_callback_url = null, string $order_id = null, string $order_description = null, string $success_url = null, string $cancel_url = null) {
        return self::execute('POST', 'invoice', [
            'price_amount'      => $price_amount,
            'price_currency'    => $price_currency,
            'pay_currency'      => $pay_currency,
            'pay_amount'        => $pay_amount,
            'ipn_callback_url'  => $ipn_callback_url,
            'order_id'          => $order_id,
            'order_description' => $order_description,
            'success_url'       => $success_url,
            'cancel_url'        => $cancel_url
        ]);
    }

    public static function getCurrencies (): array {
        return self::execute('GET', 'currencies')->currencies;
    }

    public static function isNowPayments(): bool {
        return tools::remoteIP() === '144.76.201.30';
    }

    public static function isIPNRequestValid (): bool {
        if (empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            return false;
        }
        if (!self::isNowPayments()) {
            return false;
        }
        $request_json = file_get_contents('php://input');
        if (empty($request_json)) {
            return false;
        }
        $request_data = json_decode($request_json, true);
        ksort($request_data);
        $hmac = hash_hmac("sha512", json_encode($request_data, JSON_UNESCAPED_SLASHES), trim(self::$ipn_secret));
        return $hmac == $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
    }

    /**
     * @return ipnDataInterface|mixed
     */
    public static function getIPN () {
        if (!self::isIPNRequestValid()) {
            return false;
        }
        return json_decode(file_get_contents('php://input'));
    }
}