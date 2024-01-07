<?php

namespace BPT\pay;

use BPT\settings;

class pay {
    public static function init (): void {
        if (isset(settings::$pay['crypto'])) {
            $settings = [
                'api_key'       => settings::$pay['crypto']['api_key'] ?? null,
                'ipn_secret'    => settings::$pay['crypto']['ipn_secret'] ?? null,
                'round_decimal' => settings::$pay['crypto']['round_decimal'] ?? null,
            ];
            crypto::init(...array_filter($settings));
        }
        if (isset(settings::$pay['idpay'])) {
            $settings = [
                'api_key' => settings::$pay['idpay']['api_key'] ?? null,
                'sandbox' => settings::$pay['idpay']['sandbox'] ?? null,
            ];
            idpay::init(...array_filter($settings));
        }
        if (isset(settings::$pay['zarinpal'])) {
            $settings = [
                'merchant_id' => settings::$pay['zarinpal']['sandbox'] ?? null,
                'sandbox'     => settings::$pay['zarinpal']['zarin_gate'] ?? settings::$pay['zarinpal']['zaringate'] ?? null,
                'zarin_gate'  => settings::$pay['zarinpal']['merchant_id'] ?? null,
            ];
            zarinpal::init(...array_filter($settings));
        }
    }
}