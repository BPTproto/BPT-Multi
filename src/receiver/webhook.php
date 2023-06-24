<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use CURLFile;
use JetBrains\PhpStorm\NoReturn;

/**
 * webhook class , for manage and handling webhook setter and getter
 */
class webhook extends receiver {
    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init () {
        if (settings::$multi) {
            multi::init();
        }
        else {
            if (!lock::exist('BPT-HOOK')) {
                self::processSetWebhook();
            }

            receiver::telegramVerify();
            self::checkSecret();
            logger::write('Update received , lets process it ;)');
            receiver::processUpdate();
        }
    }

    private static function deleteOldLocks() {
        lock::deleteIfExist(['BPT-MULTI-EXEC', 'BPT-MULTI-CURL', 'getUpdate', 'getUpdateHook']);
    }

    protected static function setWebhook(string $url,string $secret = '') {
        $res = BPT::setWebhook($url, settings::$certificate, max_connections: settings::$max_connection, allowed_updates: settings::$allowed_updates, drop_pending_updates: settings::$skip_old_updates, secret_token: $secret);
        if (!BPT::$status) {
            logger::write("There is some problem happened , telegram response : \n".json_encode($res),loggerTypes::ERROR);
            BPT::exit(print_r($res,true));
        }
        logger::write('Webhook was set successfully',loggerTypes::INFO);
    }

    protected static function checkURL() {
        if (!(isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI']))) {
            logger::write('For using webhook receiver , you should open this file in your webserver(by domain)',loggerTypes::ERROR);
            throw new bptException('WEBHOOK_NEED_URL');
        }
    }

    protected static function setURL(): string {
        if (isset($_GET['token'])) {
            logger::write("You can not specify token in url",loggerTypes::ERROR);
            BPT::exit("You can not specify token in url");
        }
        return (isset(settings::$certificate) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    protected static function setCertificate() {
        if (isset(settings::$certificate) && is_string(settings::$certificate)) {
            settings::$certificate = file_exists(realpath(settings::$certificate)) ? new CURLFile(settings::$certificate) : null;
        }
    }

    #[NoReturn]
    private static function processSetWebhook() {
        self::deleteOldLocks();
        self::checkURL();
        self::setCertificate();
        $url = self::setURL();
        $secret = !empty(settings::$secret) ? settings::$secret : str_replace(':','---',settings::$token);
        self::setWebhook($url,$secret);
        lock::save('BPT-HOOK', md5($secret));
        BPT::exit('Done');
    }

    private static function checkSecret() {
        $secret_hash = lock::read('BPT-HOOK');
        if ($secret_hash !== md5(self::getSecret())) {
            logger::write('This is not webhook set by BPT, webhook will reset',loggerTypes::WARNING);
            self::processSetWebhook();
        }
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function getSecret() {
        return $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? false;
    }

    /**
     * Fast end webserver process and run codes in background
     *
     * It will help you with telegram if you call it in less than 30 second of start time
     *
     * @param int $timeout set time out if you know how much your code will take , default is 1 day
     *
     * @return bool
     */
    public static function fastClose (int $timeout = 86400): bool {
        if (settings::$multi || !lock::exist('BPT-HOOK') || settings::$receiver !== \BPT\constants\receiver::WEBHOOK) {
            return false;
        }
        http_response_code(200);
        ini_set('max_execution_time', $timeout);
        set_time_limit($timeout);
        ignore_user_abort(true);
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        elseif (function_exists('litespeed_finish_request')) {
            litespeed_finish_request();
        }
        else {
            return false;
        }

        return true;
    }
}