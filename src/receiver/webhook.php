<?php

namespace BPT\receiver;

use BPT\api\telegram;
use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use CURLFile;

class webhook extends receiver {
    public static function init () {
        if (settings::$multi) {
            multi::init();
        }
        else {
            if (lock::exist('BPT-HOOK')) {
                receiver::telegramVerify();
                self::checkSecret();
                logger::write('Update received , lets process it ;)');
                receiver::processUpdate();
            }
            else {
                self::processSetWebhook();
            }
        }
    }

    private static function deleteOldLocks() {
        if (lock::exist('BPT-MULTI-EXEC')) {
            lock::delete('BPT-MULTI-EXEC');
        }
        if (lock::exist('BPT-MULTI-CURL')) {
            lock::delete('BPT-MULTI-CURL');
        }
        if (lock::exist('getUpdate')) {
            lock::delete('getUpdate');
        }
    }

    protected static function setWebhook(string $url,string $secret = '') {
        $res = telegram::setWebhook($url, settings::$certificate, max_connections: settings::$max_connection, allowed_updates: settings::$allowed_updates, drop_pending_updates: settings::$skip_old_updates, secret_token: $secret);
        if (telegram::$status) {
            logger::write('Webhook was set successfully',loggerTypes::INFO);
        }
        else {
            logger::write("There is some problem happened , telegram response : \n".json_encode($res),loggerTypes::ERROR);
            BPT::exit(print_r($res,true));
        }
    }

    protected static function checkURL() {
        if (!(isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI']))) {
            logger::write('For using webhook receiver , you should open this file in your webserver(by domain)',loggerTypes::ERROR);
            throw new bptException('WEBHOOK_NEED_URL');
        }
    }

    private static function setURL(): string {
        return (isset(settings::$certificate) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
    }

    protected static function setCertificate() {
        if (isset(settings::$certificate)) {
            if (is_string(settings::$certificate)) {
                if (file_exists(settings::$certificate)) {
                    settings::$certificate = new CURLFile(settings::$certificate);
                }
                else {
                    settings::$certificate = null;
                }
            }
        }
    }

    private static function processSetWebhook() {
        self::deleteOldLocks();
        self::checkURL();
        self::setCertificate();
        $url = self::setURL();
        $secret = settings::$secret ?? tools::randomString(64);
        self::setWebhook($url,$secret);
        lock::save('BPT-HOOK',$secret);
        BPT::exit('Done');
    }

    private static function checkSecret() {
        $secret = lock::read('BPT-HOOK');
        if ($secret !== self::getSecret()) {
            logger::write('This is not webhook set by BPT, webhook will reset',loggerTypes::WARNING);
            self::processSetWebhook();
        }
    }

    public static function getSecret() {
        return $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? false;
    }
}