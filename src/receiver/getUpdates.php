<?php

namespace BPT\receiver;

use BPT\telegram\telegram;
use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use BPT\types\update;
use JetBrains\PhpStorm\NoReturn;

/**
 * getUpdates class , For receiving updates by polling methods
 */
class getUpdates extends receiver {
    #[NoReturn]
    public static function init () {
        $last_update_id = self::loadData();
        while(true) {
            if (!lock::exist('getUpdate')) {
                $updates = telegram::getUpdates($last_update_id,allowed_updates: settings::$allowed_updates);
                if (!telegram::$status) {
                    logger::write("There is some problem happened , telegram response : \n".json_encode($updates),loggerTypes::ERROR);
                    BPT::exit(print_r($updates,true));
                }
                self::handleUpdates($updates);
                lock::save('getUpdate',BPT::$update->update_id+1);
                $last_update_id = BPT::$update->update_id+1;
            }
        }
    }

    private static function loadData(): bool|int|string {
        if (lock::exist('getUpdate')) {
            $last_update_id = lock::read('getUpdate');
        }
        else {
            self::deleteOldLocks();
            telegram::deleteWebhook();
            $last_update_id = 0;
            lock::save('getUpdate',0);
        }
        return $last_update_id;
    }

    private static function deleteOldLocks() {
        if (lock::exist('BPT-HOOK')) {
            lock::delete('BPT-HOOK');
        }
        if (lock::exist('BPT-MULTI-EXEC')) {
            lock::delete('BPT-MULTI-EXEC');
        }
        if (lock::exist('BPT-MULTI-CURL')) {
            lock::delete('BPT-MULTI-CURL');
        }
    }

    /**
     * @param update[] $updates
     */
    private static function handleUpdates(array $updates) {
        foreach ($updates as $update) {
            receiver::processUpdate($update);
        }
    }
}