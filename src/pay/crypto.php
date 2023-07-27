<?php

namespace BPT\pay;

use BPT\BPT;
use BPT\constants\callbackTypes;
use BPT\constants\cryptoCallbackActionTypes;
use BPT\constants\cryptoCallbackStatus;
use BPT\constants\cryptoStatus;
use BPT\constants\fields;
use BPT\constants\loggerTypes;
use BPT\database\mysql;
use BPT\exception\bptException;
use BPT\logger;
use BPT\pay\crypto\errorResponseInterface;
use BPT\pay\crypto\estimatePriceInterface;
use BPT\pay\crypto\estimateUpdateInterface;
use BPT\pay\crypto\invoicePaymentInterface;
use BPT\pay\crypto\invoiceResponseInterface;
use BPT\pay\crypto\ipnDataInterface;
use BPT\pay\crypto\paymentInterface;
use BPT\receiver\callback;
use BPT\settings;
use BPT\telegram\request;
use BPT\tools\tools;
use BPT\types\cryptoCallback;
use CurlHandle;
use function BPT\object;

class crypto {
    private static string $api_key = '';

    private static string $ipn_secret = '';

    private static int $round_decimal = 4;

    const API_BASE = 'https://api.nowpayments.io/v1/';

    private static CurlHandle $session;

    public static function init (string $api_key = '', string $ipn_secret = '', int $round_decimal = 4): void {
        self::$api_key = settings::$pay['crypto']['api_key'] ?? $api_key;
        self::$ipn_secret = settings::$pay['crypto']['ipn_secret'] ?? $ipn_secret;
        self::$round_decimal = settings::$pay['crypto']['round_decimal'] ?? $round_decimal;
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
                curl_setopt($session, CURLOPT_URL, self::API_BASE . $endpoint . (!empty($data) && is_array($data) ? ('?' . http_build_query($data)) : ''));
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

    /**
     * This is a method to get information about the current state of the API. Receive true if its ok and false in otherwise
     * @return bool
     */
    public static function status (): bool {
        return self::execute('GET', 'status')->message === 'OK';
    }

    /**
     * This is a method for calculating the approximate price in cryptocurrency for a given value in Fiat currency.
     * You will need to provide the initial cost in the Fiat currency (amount, currency_from) and the necessary
     * cryptocurrency (currency_to) Currently following fiat currencies are available: usd, eur, nzd, brl, gbp.
     *
     * @param int|float $amount
     * @param string    $currency_from
     * @param string    $currency_to
     *
     * @return estimatePriceInterface|errorResponseInterface|mixed
     */
    public static function getEstimatePrice (int|float $amount, string $currency_from, string $currency_to) {
        return self::execute('GET', 'estimate', [
            'amount'        => $amount,
            'currency_from' => $currency_from,
            'currency_to'   => $currency_to
        ]);
    }

    /**
     * Creates payment. With this method, your customer will be able to complete the payment without leaving your website.
     *
     * @param int|float      $price_amount        the fiat equivalent of the price to be paid in crypto
     * @param string         $price_currency      the fiat currency in which the price_amount is specified (usd, eur, etc).
     * @param string         $pay_currency        the cryptocurrency in which the pay_amount is specified (btc, eth, etc).
     * @param int|float|null $pay_amount          the amount that users have to pay for the order stated in crypto
     * @param string|null    $ipn_callback_url    url to receive callbacks, should contain "http" or "https"
     * @param string|null    $order_id            inner store order ID
     * @param string|null    $order_description   inner store order description
     * @param string|null    $purchase_id         id of purchase for which you want to create aother payment, only used for several payments for one order
     * @param string|null    $payout_address      usually the funds will go to the address you specify in your Personal account. In case you want to receive funds on another address, you can specify it in this parameter.
     * @param string|null    $payout_currency     currency of your external payout_address, required when payout_adress is specified.
     * @param string|null    $payout_extra_id     extra id or memo or tag for external payout_address.
     * @param bool|null      $fixed_rate          boolean, can be true or false. Required for fixed-rate exchanges.
     * @param bool|null      $is_fee_paid_by_user boolean, can be true or false. Required for fixed-rate exchanges with all fees paid by users.
     *
     * @return invoicePaymentInterface|errorResponseInterface|mixed
     */
    public static function createPayment (int|float $price_amount, string $price_currency, string $pay_currency, int|float $pay_amount = null, string $ipn_callback_url = null, string $order_id = null, string $order_description = null, string $purchase_id = null, string $payout_address = null, string $payout_currency = null, string $payout_extra_id = null, bool $fixed_rate = null, bool $is_fee_paid_by_user = null) {
        return self::execute('POST', 'payment', [
            'price_amount'        => $price_amount,
            'price_currency'      => $price_currency,
            'pay_currency'        => $pay_currency,
            'pay_amount'          => $pay_amount,
            'ipn_callback_url'    => $ipn_callback_url,
            'order_id'            => $order_id,
            'order_description'   => $order_description,
            'purchase_id'         => $purchase_id,
            'payout_address'      => $payout_address,
            'payout_currency'     => $payout_currency,
            'payout_extra_id'     => $payout_extra_id,
            'fixed_rate'          => $fixed_rate,
            'is_fee_paid_by_user' => $is_fee_paid_by_user,
        ]);
    }

    /**
     * Creates payment by invoice. With this method, your customer will be able to complete the payment without leaving your website.
     *
     * @param string      $iid invoice id
     * @param string      $pay_currency the cryptocurrency in which the pay_amount is specified (btc, eth, etc).
     * @param string|null $purchase_id id of purchase for which you want to create aother payment, only used for several payments for one order
     * @param string|null $order_description inner store order description
     * @param string|null $customer_email user email to which a notification about the successful completion of the payment will be sent
     * @param string|null $payout_address usually the funds will go to the address you specify in your Personal account.
     * @param string|null $payout_extra_id extra id or memo or tag for external payout_address.
     * @param string|null $payout_currency currency of your external payout_address, required when payout_adress is specified.
     *
     * @return invoicePaymentInterface|errorResponseInterface|mixed
     */
    public static function createInvoicePayment (string $iid, string $pay_currency, string $purchase_id = null, string $order_description = null, string $customer_email = null, string $payout_address = null, string $payout_extra_id = null, string $payout_currency = null) {
        return self::execute('POST', 'invoice-payment', [
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
     * This endpoint is required to get the current estimate on the payment, and update the current estimate.
     * Please note! Calling this estimate before expiration_estimate_date will return the current estimate, it won’t be updated.
     *
     * @param int $paymentID payment ID, for which you want to get the estimate
     *
     * @return estimateUpdateInterface|errorResponseInterface|mixed
     */
    public static function updateEstimatePrice (int $paymentID) {
        return self::execute('POST', 'payment/' . $paymentID . '/update-merchant-estimate');
    }

    /**
     * Get the actual information about the payment.
     *
     * @param int $paymentID payment ID, for which you want to get the status
     *
     * @return paymentInterface|errorResponseInterface|mixed
     */
    public static function getPaymentStatus (int $paymentID) {
        return self::execute('GET', 'payment/' . $paymentID);
    }

    /**
     * Get the minimum payment amount for a specific pair.
     *
     * @param string $currency_from
     * @param string $currency_to
     *
     * @return float
     */
    public static function getMinimumPaymentAmount (string $currency_from, string $currency_to): float {
        return self::execute('GET', 'min-amount', [
            'currency_from' => $currency_from,
            'currency_to'   => $currency_to
        ])->min_amount;
    }

    /**
     * Creates an invoice. With this method, the customer is required to follow the generated url to complete the payment.
     *
     * @param int|float   $price_amount
     * @param string      $price_currency
     * @param string|null $pay_currency
     * @param string|null $ipn_callback_url
     * @param string|null $order_id
     * @param string|null $order_description
     * @param string|null $success_url
     * @param string|null $cancel_url
     *
     * @return invoiceResponseInterface|errorResponseInterface|mixed
     */
    public static function createInvoice (int|float $price_amount, string $price_currency, string $pay_currency = null, string $ipn_callback_url = null, string $order_id = null, string $order_description = null, string $success_url = null, string $cancel_url = null) {
        return self::execute('POST', 'invoice', [
            'price_amount'      => $price_amount,
            'price_currency'    => $price_currency,
            'pay_currency'      => $pay_currency,
            'ipn_callback_url'  => $ipn_callback_url,
            'order_id'          => $order_id,
            'order_description' => $order_description,
            'success_url'       => $success_url,
            'cancel_url'        => $cancel_url
        ]);
    }

    /**
     * This is a method for obtaining information about all cryptocurrencies available for payments.
     *
     * @return array
     */
    public static function getCurrencies (): array {
        return self::execute('GET', 'currencies')->currencies;
    }

    /**
     * This is a method to obtain detailed information about all cryptocurrencies available for payments.
     *
     * @return array
     */
    public static function getFullCurrencies (): array {
        return self::execute('GET', 'full-currencies')->currencies;
    }

    /**
     * This is a method for obtaining information about the cryptocurrencies available for payments.
     * Shows the coins you set as available for payments in the "coins settings" tab on your personal account.
     *
     * @return array
     */
    public static function getAvailableCheckedCurrencies (): array {
        return self::execute('GET', 'merchant/coins')->currencies;
    }

    /**
     * Check remote ip with nowPayments IPN ip
     *
     * @return bool
     */
    public static function isNowPayments(): bool {
        return tools::remoteIP() === '144.76.201.30';
    }

    /**
     * Check is IPN valid or not
     *
     * @return bool
     */
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
     * First it will check if IPN is valid or not, if its valid , then it will return IPN data
     *
     * @return ipnDataInterface|mixed
     */
    public static function getIPN () {
        if (!self::isIPNRequestValid()) {
            return false;
        }
        return json_decode(file_get_contents('php://input'));
    }

    protected static function createOrder (float|int $amount, int $user_id, string $description): int|string {
        if (!mysql::getMysqli()) {
            logger::write("crypto::ezPay function used\ncreating order needed mysql connection in our mysql class", loggerTypes::ERROR);
            throw new bptException('MYSQL_CONNECTION_NEEDED');
        }

        mysql::insert('orders', ['user_id', 'type', 'amount', 'description'], [$user_id, callbackTypes::CRYPTO, $amount, $description]);

        return mysql::insertId();
    }

    protected static function getOrder (int $order_id): bool|object {
        if (!mysql::getMysqli()) {
            logger::write("crypto::ezPay function used\ncreating order needed mysql connection in our mysql class", loggerTypes::ERROR);
            throw new bptException('MYSQL_CONNECTION_NEEDED');
        }
        $order = mysql::select('orders', '*', ['id' => $order_id], 1);
        if ($order->num_rows < 1) {
            return false;
        }
        return $order->fetch_object();
    }

    /**
     * An easy way to create invoice
     *
     * Processing and authorization of ipn callbacks will be done by library
     *
     * Note : You must activate ipn in your nowPayment account, and you must set ipn_secret in the settings
     *
     * The related callback will be sent to your cryptoCallback method in handler class
     *
     * @param float|int $amount
     * @param int|null  $user_id
     * @param string    $currency
     * @param string    $description
     * @param bool      $direct_url
     * @param bool      $one_time_url
     *
     * @return bool|string
     * @throws bptException
     */
    public static function ezPay (float|int $amount, int $user_id = null, string $currency = 'usd', string $description = 'Invoice created by BPT library', bool $direct_url = false, bool $one_time_url = true): bool|string {
        if (empty(self::$ipn_secret)) {
            logger::write("crypto::ezPay function used\nyou must set ipn_secret to use this", loggerTypes::ERROR);
            return false;
        }
        if ($amount < 0) {
            logger::write("crypto::ezPay function used\namount must be bigger then 0", loggerTypes::ERROR);
            return false;
        }
        $user_id = $user_id ?? request::catchFields(fields::USER_ID);
        $order_id = self::createOrder($amount, $user_id, $description);
        $data = ['type' => callbackTypes::CRYPTO, 'action_type' => cryptoCallbackActionTypes::CALLBACK, 'amount' => $amount, 'currency' => $currency, 'user_id' => $user_id, 'order_id' => $order_id];
        $callback_url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?data='.urlencode(callback::encodeData($data));

        $data = ['type' => callbackTypes::CRYPTO, 'action_type' => cryptoCallbackActionTypes::SUCCESS, 'amount' => $amount, 'currency' => $currency, 'user_id' => $user_id, 'order_id' => $order_id];
        $success_url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?data='.urlencode(callback::encodeData($data));

        $invoice_id = self::createInvoice($amount, $currency, null, $callback_url, $order_id, $description, $success_url)->id;

        $extra_info = [
            'invoice_id'       => $invoice_id,
            'currency'         => $currency,
            'related_payments' => [],
            'total_paid'      => 0,
        ];
        if (!$direct_url && $one_time_url) {
            $extra_info['redirected'] = false;
        }

        mysql::update('orders', [
            'extra_info' => json_encode($extra_info),
        ], ['id' => $order_id], 1);

        if ($direct_url) {
            return 'https://nowpayments.io/payment/?iid='. $invoice_id;
        }

        $data = ['type' => callbackTypes::CRYPTO, 'action_type' => cryptoCallbackActionTypes::REDIRECT, 'amount' => $amount, 'currency' => $currency, 'user_id' => $user_id, 'order_id' => $order_id];
        return 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?data='.urlencode(callback::encodeData($data));
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function callbackProcess (array $data) {
        if (!isset($data['action_type'])) {
            return false;
        }

        if (!isset($data['order_id'])) {
            return false;
        }

        $action_type = $data['action_type'];
        $order_id = $data['order_id'];

        if ($action_type === cryptoCallbackActionTypes::REDIRECT) {
            $order = self::getOrder($order_id);
            $extra_info = json_decode($order->extra_info);
            if (isset($extra_info->redirected)) {
                if ($extra_info->redirected) {
                    BPT::exit('This link is one time only, Receive another link');
                }
            }
            $url = 'https://nowpayments.io/payment/?iid='. $extra_info->invoice_id;

            @header('Location: ' . $url);
            die("<meta http-equiv='refresh' content='0; url=$url' /><script>window.location.href = '$url';</script>");
        }

        if ($action_type === cryptoCallbackActionTypes::CALLBACK) {
            $ipn = self::getIPN();

            if ($ipn->payment_status !== cryptoStatus::FINISHED && $ipn->payment_status !== cryptoStatus::PARTIALLY_PAID ) {
                die();
            }

            $payment_id = $ipn->payment_id;

            $payment = self::getPaymentStatus($payment_id);

            if (isset($payment->status) && !$payment->status) {
                die();
            }

            if ($payment->payment_status !== cryptoStatus::FINISHED && $payment->payment_status !== cryptoStatus::PARTIALLY_PAID) {
                die();
            }

            $order = self::getOrder($order_id);
            $extra_info = json_decode($order->extra_info, true);
            if (isset($extra_info['related_payments'][$payment_id])) {
                die();
            }

            $paid = round(isset($payment->actually_paid_at_fiat) && $payment->actually_paid_at_fiat > 0 ? $payment->actually_paid_at_fiat : $payment->actually_paid/$payment->pay_amount*$payment->price_amount, self::$round_decimal);
            $extra_info['related_payments'][$payment_id] = $paid;
            $extra_info['total_paid'] += $paid;
            mysql::update('orders', ['extra_info' => json_encode($extra_info)], ['id' => $order_id], 1);

            $callback_data = [
                'status' => 'unknown',
                'order_id' => $order_id,
                'user_id' => $order->user_id,
                'description' => $order->description,
                'real_amount' => $order->amount,
                'currency' => $extra_info['currency'],
                'paid_amount' => $paid,
                'total_paid' => $extra_info['total_paid']
            ];

            if ($payment->payment_status === cryptoStatus::PARTIALLY_PAID) {
                $callback_data['status'] = $extra_info['total_paid'] > $order->amount ? cryptoCallbackStatus::EXTRA_PAID : ($extra_info['total_paid'] == $order->amount ? cryptoCallbackStatus::FINISHED : cryptoCallbackStatus::PARTIALLY_PAID);
            }

            if ($payment->payment_status === cryptoStatus::FINISHED) {
                $callback_data['status'] = $extra_info['total_paid'] <= $order->amount ? cryptoCallbackStatus::FINISHED : cryptoCallbackStatus::EXTRA_PAID;
            }

            $callback_data = object(...$callback_data);
            $callback_data = new cryptoCallback($callback_data);

            callback::callHandler('cryptoCallback', $callback_data);
            return true;
        }

        if ($action_type === cryptoCallbackActionTypes::SUCCESS) {
            if (!isset($_GET['NP_id'])) {
                return false;
            }

            if (!is_numeric($_GET['NP_id'])) {
                return false;
            }

            $payment_id = $_GET['NP_id'];

            $payment = self::getPaymentStatus($payment_id);

            if (isset($payment->status) && !$payment->status) {
                return false;
            }

            if ($payment->payment_status !== cryptoStatus::FINISHED) {
                return false;
            }

            $order = self::getOrder($order_id);
            $extra_info = json_decode($order->extra_info);

            $callback_data = [
                'status' => cryptoCallbackStatus::SUCCESS,
                'order_id' => $order_id,
                'user_id' => $order->user_id,
                'description' => $order->description,
                'real_amount' => $order->amount,
                'currency' => $extra_info->currency,
                'paid_amount' => round(isset($payment->actually_paid_at_fiat) && $payment->actually_paid_at_fiat > 0 ? $payment->actually_paid_at_fiat : $payment->actually_paid/$payment->pay_amount*$payment->price_amount, self::$round_decimal),
                'total_paid' => $extra_info->total_paid
            ];
            $callback_data = object(...$callback_data);
            $callback_data = new cryptoCallback($callback_data);

            callback::callHandler('cryptoCallback', $callback_data);
            return true;
        }
    }
}