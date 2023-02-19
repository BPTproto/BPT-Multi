<?php

namespace BPT\pay;

use BPT\settings;

class pay {
    public static function init (): void {
        if (isset(settings::$pay['crypto'])) {
            crypto::init();
        }
        if (isset(settings::$pay['idpay'])) {
            idpay::init();
        }
    }
}