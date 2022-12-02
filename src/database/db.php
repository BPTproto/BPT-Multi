<?php

namespace BPT\database;

use BPT\constants\dbTypes;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\logger;
use BPT\settings;

/**
 * db class , for manage and handling databases
 */
class db {
    private static bool $active = false;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init (): void {
        if (!empty(settings::$db)) {
            if (!isset(settings::$db['type'])) {
                settings::$db['type'] = dbTypes::JSON;
            }
            switch (settings::$db['type']) {
                case dbTypes::JSON:
                    json::init();
                    break;
                case dbTypes::MYSQL:
                    mysql::init();
                    break;
                default:
                    logger::write('DB type is wrong', loggerTypes::ERROR);
                    throw new bptException('DB_TYPE_WRONG');
            }
            self::$active = true;
        }
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function process(): void {
        if (self::$active) {
            switch (settings::$db['type']) {
                case dbTypes::JSON:
                    json::process();
                    break;
                case dbTypes::MYSQL:
                    mysql::process();
                    break;
            }
        }
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function save(): void {
        if (self::$active && settings::$db['type'] === dbTypes::JSON) {
            json::save();
        }
    }
}