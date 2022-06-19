<?php

namespace BPT\receiver;

use BPT\lock;

class multi {
    public static function init() {
        if (lock::exist('BPT-MULTI')) {

        }
        else {

        }
    }
}