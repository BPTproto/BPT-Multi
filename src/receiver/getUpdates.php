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
        lock::set('getUpdateHook');
        while(true) {
            if (!lock::exist('getUpdateHook')) {
                break;
            }
            $updates = telegram::getUpdates($last_update_id,allowed_updates: settings::$allowed_updates);
            if (!telegram::$status) {
                logger::write("There is some problem happened , telegram response : \n".json_encode($updates),loggerTypes::ERROR);
                BPT::exit(print_r($updates,true));
            }
            self::handleUpdates($updates);
            $last_update_id = BPT::$update->update_id+1;
            lock::save('getUpdate',$last_update_id);
        }
    }

    private static function loadData(): bool|int|string {
        if (lock::exist('getUpdate')) {
            return lock::read('getUpdate');
        }
        self::deleteOldLocks();
        telegram::deleteWebhook();
        lock::save('getUpdate',0);
        return 0;
    }

    private static function deleteOldLocks() {
        lock::deleteIfExist(['BPT-HOOK', 'BPT-MULTI-EXEC', 'BPT-MULTI-CURL']);
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