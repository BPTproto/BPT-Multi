<?php

namespace BPT\receiver;

use BPT\lock;
use BPT\logger;
use BPT\receiver\multi\exec;
use BPT\receiver\multi\curl;

/**
 * multi class , for multiprocessing webhook updates
 */
class multi extends webhook {
    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
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
        lock::deleteIfExist(['BPT', 'getUpdate', 'getUpdateHook']);
    }
}