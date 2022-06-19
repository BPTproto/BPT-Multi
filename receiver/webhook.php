<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use BPT\types\update;

class webhook {
    public static function init () {
        if (lock::exist('BPT-HOOK')) {
            self::telegramVerify();
            BPT::$update = self::processUpdate();
        }
        else {
            self::deleteOldLocks();
        }
    }

    public static function telegramVerify() {
        if (settings::$telegram_verify) {
            if (!tools::isTelegram($_SERVER['REMOTE_ADDR'])) {
                logger::write('not authorized access denied. IP : '.$_SERVER['REMOTE_ADDR'],'error');
                BPT::close();
            }
        }
    }

    public static function processUpdate(): update {
        $update = json_decode(file_get_contents("php://input"));
        if ($update) {
            return new update($update);
        }
        else {
            BPT::close();
        }
    }

    public static function deleteOldLocks() {
        if (lock::exist('BPT-MULTI')) {
            lock::delete('BPT-MULTI');
        }
        if (lock::exist('BPT-MULTI-2')) {
            lock::delete('BPT-MULTI-2');
        }
        if (lock::exist('getUpdate')) {
            lock::delete('getUpdate');
        }
    }
}