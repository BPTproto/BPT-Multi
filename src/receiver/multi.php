<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\lock;
use BPT\logger;
use BPT\receiver\multi\exec;
use BPT\receiver\multi\curl;

class multi extends webhook {
    public static function init() {
        if (lock::exist('BPT-MULTI-EXEC')) {
            self::setUpdate(exec::init());
        }
        elseif(lock::exist('BPT-MULTI-CURL')) {
            self::setUpdate(curl::init());
        }
        else {
            self::deleteOldLocks();
            self::checkURL();
            self::setCertificate();
            exec::support() ? exec::install() : curl::install();
        }
    }

    private static function setUpdate(string $update) {
        receiver::processUpdate($update);
        logger::write('Update received , lets process it ;)');
    }

    private static function deleteOldLocks() {
        if (lock::exist('BPT')) {
            lock::delete('BPT');
        }
        if (lock::exist('getUpdate')) {
            lock::delete('getUpdate');
        }
    }
}