<?php

namespace BPT;

use BPT\constants\loggerTypes;
use BPT\constants\receiver;
use BPT\db\db;
use BPT\receiver\getUpdates;
use BPT\receiver\webhook;
use CURLFile;
use Error;
use mysqli;
use stdClass;
use TypeError;

class settings {
    public static string $token = '';

    //public static bool $auto_update = true;

    public static bool $logger = true;

    public static int $log_size = 10;

    public static string|CURLFile|null $certificate = null;

    public static bool $handler = true;

    public static bool $security = false;

    public static bool $secure_folder = false;

    //public static bool $array_update = false;

    public static bool $split_update = true;

    public static bool $multi = false;

    //public static bool $debug = false;

    public static bool $telegram_verify = true;

    public static int $max_connection = 40;

    public static string $base_url = 'https://api.telegram.org/bot';

    public static string $down_url = 'https://api.telegram.org/file/bot';

    public static int $forgot_time = 100;

    public static string $receiver = receiver::WEBHOOK;

    public static array $allowed_updates = ['message', 'edited_channel_post', 'callback_query', 'inline_query'];

    public static array|mysqli|null $db = ['type' => 'json', 'file_name' => 'BPT-DB.json'];


    public static function init (array|stdClass $settings) {
        $settings = (array) $settings;

        if (!(isset($settings['logger']) && $settings['logger'] == false)) {
            logger::init(isset($settings['log_size']) && is_numeric($settings['log_size']) ? $settings['log_size'] : self::$log_size);
        }

        foreach ($settings as $setting => $value) {
            try{
                self::$$setting = $value;
            }
            catch (TypeError){
                logger::write("$setting setting has wrong type , its set to default value",loggerTypes::WARNING);
            }
            catch (Error){
                logger::write("$setting setting is not one of library settings",loggerTypes::WARNING);
            }
        }

        if (self::$token !== '') {
            if (tools::isToken(self::$token)) {
                self::security();
                self::secureFolder();
                self::db();
                self::$receiver !== receiver::GETUPDATES ? self::webhook() : self::getUpdates();
            }
            else {
                logger::write('token format is not right, check it and try again',loggerTypes::ERROR);
            }
        }
        else {
            logger::write('You must specify token parameter in settings',loggerTypes::ERROR);
        }
    }

    private static function security() {
        if (self::$security) {
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
    }

    private static function secureFolder() {
        if (self::$secure_folder) {
            $address = explode('/', $_SERVER['REQUEST_URI']);
            unset($address[count($address) - 1]);
            $address = implode('/', $address) . '/BPT.php';
            $text = "ErrorDocument 404 $address\nErrorDocument 403 $address\n Options -Indexes\n  Order Deny,Allow\nDeny from all\nAllow from 127.0.0.1\n<Files *.php>\n    Order Allow,Deny\n    Allow from all\n</Files>";
            if (!file_exists('.htaccess') || filesize('.htaccess') != strlen($text)) {
                file_put_contents('.htaccess', $text);
            }
        }
    }

    private static function db() {
        if (!empty(self::$db)) {
            db::init(self::$db);
        }
    }

    private static function getUpdates() {
        if (self::$handler) {
            getUpdates::init();
        }
        else {
            logger::write('You can\'t use getUpdates receiver when handler is off , use webhook or use handler',loggerTypes::ERROR);
        }
    }

    private static function webhook() {
        //self::$multi ? multi::init() : self::getUpdates();
        webhook::init();
    }
}
