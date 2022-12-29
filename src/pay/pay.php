<?php

namespace BPT\pay;

use BPT\settings;

class pay {
    public static function init (): void {
        if (!empty(settings::$pay)) {
            if (!isset(settings::$pay['crypto'])) {
                return;
            }
            crypto::init();
        }
    }
}