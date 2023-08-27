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
        if (!isset(settings::$db['type'])) {
            settings::$db['type'] = dbTypes::JSON;
        }
        switch (settings::$db['type']) {
            case dbTypes::JSON:
                $settings = [
                    'bot_name'   => settings::$name,
                    'global'     => settings::$db['global'] ?? null,
                    'user'       => settings::$db['user'] ?? null,
                    'group_user' => settings::$db['group_user'] ?? null,
                    'group'      => settings::$db['group'] ?? null,
                    'supergroup' => settings::$db['supergroup'] ?? null,
                    'channel'    => settings::$db['channel'] ?? null,
                ];
                json::init(...array_filter($settings, fn ($value)  => $value !== null));
                break;
            case dbTypes::MYSQL:
                $settings = [
                    'host'         => settings::$db['host'] ?? null,
                    'username'     => settings::$db['username'] ?? settings::$db['user'] ?? null,
                    'password'     => settings::$db['password'] ?? settings::$db['pass'] ?? null,
                    'dbname'       => settings::$db['dbname'] ?? null,
                    'auto_process' => settings::$db['auto_process'] ?? null,
                    'port'         => settings::$db['port'] ?? null,
                    'auto_load'    => settings::$db['auto_load'] ?? null,
                ];
                mysql::init(...array_filter($settings, fn ($value)  => $value !== null));
                break;
            default:
                logger::write('DB type is wrong', loggerTypes::ERROR);
                throw new bptException('DB_TYPE_WRONG');
        }
        self::$active = true;
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