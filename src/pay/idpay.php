<?php

namespace BPT\pay;

use BPT\pay\idpay\errorInterface;
use BPT\pay\idpay\paymentCreateInterface;
use BPT\pay\idpay\paymentInterface;
use BPT\pay\idpay\paymentListInterface;
use CurlHandle;

class idpay {
    const API_BASE = 'https://api.idpay.ir/v1.1/';

    private static CurlHandle $session;

    public static function init (string $api_key = '', bool $sandbox = false): void {
        self::$session = curl_init();
        curl_setopt(self::$session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt(self::$session, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-KEY: ' . $api_key,
            'X-SANDBOX: ' . (int) ($sandbox),
        ]);
        curl_setopt(self::$session, CURLOPT_POST, true);
    }

    private static function execute (string $endpoint, array $params) {
        foreach ($params as $key => $value) {
            if (empty($value)) {
                unset($params[$key]);
            }
        }

        $session = self::$session;

        curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($session, CURLOPT_URL, self::API_BASE . $endpoint);

        return json_decode(curl_exec($session));
    }

    /**
     * @return paymentCreateInterface|errorInterface|object|bool
     */
    public static function createPayment (string $order_id, int $amount, string $name = '', string $phone = '', string $mail = '', string $desc = '', string $callback = ''): object|bool {
        return self::execute('payment', [
            'order_id' => $order_id,
            'amount'   => $amount,
            'name'     => $name,
            'phone'    => $phone,
            'mail'     => $mail,
            'desc'     => $desc,
            'callback' => $callback,
        ]);
    }

    /**
     * @return paymentInterface|errorInterface|object|bool
     */
    public static function paymentDetail (string $id, string $order_id): object {
        return self::execute('payment/inquiry', [
            'order_id' => $order_id,
            'id'       => $id
        ]);
    }

    /**
     * @return paymentInterface|errorInterface|object|bool
     */
    public static function paymentConfirm (string $id, string $order_id): object {
        return self::execute('payment/verify', [
            'order_id' => $order_id,
            'id'       => $id
        ]);
    }

    /**
     * @return paymentListInterface|errorInterface|object|bool
     */
    public static function paymentList (int $page = 0, int $page_size = 25): object {
        return self::execute('payment/transactions', [
            'page'      => $page,
            'page_size' => $page_size
        ]);
    }

    public static function processCallback (): bool|int {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $_POST;
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = $_GET;
        }
        else {
            return false;
        }

        if (empty($response['status']) || empty($response['id']) || empty($response['track_id']) || empty($response['order_id'])) {
            return false;
        }
        if ($response['status'] != 10) {
            return $response['status'];
        }

        $detail = self::paymentDetail($response['id'], $response['order_id']);
        if (!isset($detail->status)) {
            return false;
        }
        if ($detail->status != 10) {
            return $detail->status;
        }

        return self::paymentConfirm($response['id'], $response['order_id'])->status;
    }
}