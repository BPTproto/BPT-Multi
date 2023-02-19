<?php

namespace BPT\pay;

use BPT\pay\zarinpal\refundInterface;
use BPT\pay\zarinpal\requestInterface;
use BPT\pay\zarinpal\unverifiedInterface;
use BPT\pay\zarinpal\verifyInterface;
use BPT\settings;
use CurlHandle;
use JetBrains\PhpStorm\NoReturn;

class zarinpal {
    public static string $merchant_id = '';
    public static bool $sandbox = false;
    public static bool $zarin_gate = false;

    const API_BASE = 'https://api.zarinpal.com/pg/v4/payment/';

    const SANDBOX_API_BASE = 'https://sandbox.zarinpal.com/pg/v4/payment/';

    const PAY_BASE = 'https://www.zarinpal.com/pg/StartPay/';

    const SANDBOX_PAY_BASE = 'https://sandbox.zarinpal.com/pg/StartPay/';

    private static CurlHandle $session;

    public static function init (string $merchant_id = '', bool $sandbox = false, bool $zarin_gate = false): void {
        self::$sandbox = settings::$pay['zarinpal']['sandbox'] ?? $sandbox;
        self::$zarin_gate = settings::$pay['zarinpal']['zarin_gate'] ?? settings::$pay['zarinpal']['zaringate'] ?? $zarin_gate;
        self::$merchant_id = settings::$pay['zarinpal']['merchant_id'] ?? $merchant_id;
        self::$session = curl_init();
        curl_setopt(self::$session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt(self::$session, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt(self::$session, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt(self::$session, CURLOPT_POST, true);
    }

    private static function getUrl (string $endpoint, bool $pay = false): string {
        if ($pay) {
            $url = self::$sandbox ? self::SANDBOX_PAY_BASE : self::PAY_BASE;
        }
        else {
            $url = self::$sandbox ? self::SANDBOX_API_BASE : self::API_BASE;
        }
        $url .= $endpoint;
        if (self::$zarin_gate) {
            $url .= '/ZarinGate';
        }
        return $url;
    }

    private static function execute (string $endpoint, array $params = []): object {
        foreach ($params as $key => $value) {
            if (empty($value)) {
                unset($params[$key]);
            }
        }

        $session = self::$session;

        $params['merchant_id'] = self::$merchant_id;

        if (isset($params['authorization'])) {
            curl_setopt(self::$session, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'authorization: Bearer '.$params['authorization']
            ]);
            unset($params['authorization']);
        }

        curl_setopt($session, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($session, CURLOPT_URL, self::getUrl($endpoint));

        $result = json_decode(curl_exec($session));
        if (isset($result->data)) {
            return $result->data;
        }

        return $result;
    }

    /**
     * @return object|requestInterface
     */
    public static function request (int $amount, string $description, string $callback_url, array $metadata = [], string $mobile = '', string $email = '', array $wages = [], int $card_pan = null, string $currency = ''): object {
        return self::execute('/request.json', [
            'amount'       => $amount,
            'description'  => $description,
            'callback_url' => $callback_url,
            'metadata'     => $metadata,
            'mobile'       => $mobile,
            'email'        => $email,
            'wages'        => $wages,
            'card_pan'     => $card_pan,
            'currency'     => $currency,
        ]);
    }

    public static function payURL (string|array $authority): bool|string {
        if (is_array($authority)) {
            if (!isset($authority->authority)) {
                return false;
            }
            $authority = $authority->authority;
        }
        return self::getUrl("/$authority", true);
    }

    /**
     * @return object|verifyInterface
     */
    public static function verify (int $amount, string $authority): object {
        return self::execute('/verify.json', [
            'amount'    => $amount,
            'authority' => $authority
        ]);
    }

    /**
     * @return object|unverifiedInterface
     */
    public static function unVerified (): object {
        return self::execute('/unVerified.json');
    }

    /**
     * @return object|refundInterface
     */
    public static function refund (string $authorization, string $authority): object {
        return self::execute('/refund.json', [
            'authorization' => $authorization,
            'authority'     => $authority
        ]);
    }

    #[NoReturn]
    public static function redirect (string $url): void {
        @header('Location: ' . $url);
        die("<meta http-equiv='refresh' content='0; url=$url' /><script>window.location.href = '$url';</script>");
    }

    public static function processCallback (int $amount): object|bool|int {
        if (!isset($_GET['Authority']) || !isset($_GET['Status'])) {
            return false;
        }

        if ($_GET['status'] != 'OK') {
            return false;
        }

        $detail = self::verify($amount, $_GET['Authority']);

        if (isset($detail->code) && $detail->code != 100) {
            return $detail->code;
        }

        return $detail;
    }
}
