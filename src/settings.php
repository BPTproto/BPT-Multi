<?php

namespace BPT;

use BPT\constants\loggerTypes;
use BPT\constants\receiver;
use BPT\database\db;
use BPT\exception\bptException;
use BPT\pay\pay;
use BPT\receiver\getUpdates;
use BPT\receiver\webhook;
use BPT\settings\easySettings;
use BPT\tools\tools;
use CURLFile;
use Error;
use TypeError;

/**
 * BPT settings class , manage and handle settings and other staff
 */
class settings {
    public static string $token = '';

    public static int $bot_id = 0;

    public static string $name = '';

    public static bool $logger = true;

    public static int $log_size = 10;

    public static string|CURLFile|null $certificate = null;

    public static bool $handler = true;

    public static bool $security = false;

    public static bool $secure_folder = false;

    public static bool $multi = false;

    public static bool $telegram_verify = true;

    public static bool $cloudflare_verify = false;

    public static bool $arvancloud_verify = false;

    public static bool $skip_old_updates = true;

    public static string $secret = '';

    public static int $max_connection = 40;

    public static string $base_url = 'https://api.telegram.org';

    public static string $down_url = 'https://api.telegram.org/file';

    public static string $default_parse_mode = '';

    public static bool $default_protect_content = false;

    public static int $ignore_updates_older_then = 0;

    public static int $forgot_time = 100;

    public static int $base_timeout = 1000;

    public static string|null $receiver = receiver::WEBHOOK;

    public static array $allowed_updates = ['message', 'edited_channel_post', 'callback_query', 'inline_query'];

    public static bool $use_types_classes = true;

    public static array|null $db = null;

    public static array|null $pay = null;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init (array|easySettings $settings): void {
        if (!is_array($settings)) {
            $settings = $settings->getSettings();
        }
        foreach ($settings as $setting => $value) {
            try {
                if ($setting === 'name') {
                    if (!is_dir(realpath('bots_files'))) {
                        mkdir('bots_files');
                    }
                    if (!is_dir(realpath('bots_files/' . $value))) {
                        mkdir('bots_files/' . $value);
                    }
                    $value = 'bots_files/' . $value . '/';
                }
                self::$$setting = $value;
            }
            catch (TypeError) {
                logger::write("$setting setting has wrong type , its set to default value", loggerTypes::WARNING);
            }
            catch (Error) {
                logger::write("$setting setting is not one of library settings", loggerTypes::WARNING);
            }
        }
        if (settings::$logger) {
            logger::init(self::$name, self::$log_size);
        }
        if (self::$token === '') {
            $secret = str_replace('---', ':', webhook::getSecret());
            if (!lock::exist('BPT-HOOK') || !tools::isToken($secret)) {
                logger::write('You must specify token parameter in settings', loggerTypes::ERROR);
                throw new bptException('TOKEN_NOT_FOUND');
            }
            self::$token = $secret;
        }
        if (!tools::isToken(self::$token)) {
            logger::write('token format is not right, check it and try again', loggerTypes::ERROR);
            throw new bptException('TOKEN_NOT_TRUE');
        }
        self::$bot_id = explode(':', self::$token)[0];
        self::security();
        self::secureFolder();
        if (!empty(settings::$db)) {
            db::init();
        }
        if (!empty(settings::$pay)) {
            pay::init();
        }
        if (!empty(self::$receiver)) {
            self::$receiver !== receiver::GETUPDATES ? webhook::init() : self::getUpdates();
        }
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function done (): void {
        if (!self::$logger) {
            return;
        }
        $estimated = round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2);
        $status_message = match (true) {
            $estimated < 100 => 'Excellent',
            $estimated < 500 => 'Very good',
            $estimated < 1000 => 'Good',
            $estimated < 3000 => 'You could do better',
            default => 'You need to do something , its take to much time'
        };
        $type = $estimated > 3000 ? loggerTypes::WARNING : loggerTypes::NONE;
        logger::write("BPT Done in $estimated ms , $status_message", $type);
    }

    private static function security (): void {
        if (!self::$security) {
            return;
        }
        ini_set('magic_quotes_gpc', 'off');
        ini_set('sql.safe_mode', 'on');
        ini_set('max_execution_time', 30);
        ini_set('max_input_time', 30);
        ini_set('memory_limit', '20M');
        ini_set('post_max_size', '8K');
        ini_set('expose_php', 'off');
        ini_set('file_uploads', 'off');
        ini_set('display_errors', 0);
        ini_set('error_reporting', 0);
    }

    private static function secureFolder (): void {
        if (!self::$secure_folder) {
            return;
        }
        $address = explode('/', $_SERVER['REQUEST_URI']);
        unset($address[count($address) - 1]);
        $address = implode('/', $address) . '/BPT.php';
        $text = "ErrorDocument 404 $address\nErrorDocument 403 $address\n Options -Indexes\n  Order Deny,Allow\nDeny from all\nAllow from 127.0.0.1\n<Files *.php>\n    Order Allow,Deny\n    Allow from all\n</Files>";
        $htaccess = realpath('.htaccess');
        if (!file_exists($htaccess) || filesize($htaccess) != strlen($text)) {
            file_put_contents('.htaccess', $text);
        }
    }

    private static function getUpdates (): void {
        if (!self::$handler) {
            logger::write('You can\'t use getUpdates receiver when handler is off , use webhook or use handler', loggerTypes::ERROR);
            throw new bptException('GETUPDATE_NEED_HANDLER');
        }
        getUpdates::init();
    }
}
