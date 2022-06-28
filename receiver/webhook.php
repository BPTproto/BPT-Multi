<?php

namespace BPT\receiver;

use BPT\api\telegram;
use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use CURLFile;

class webhook extends receiver {
    public static function init () {
        if (settings::$multi) {
            multi::init();
        }
        else {
            if (lock::exist('BPT-HOOK')) {
                receiver::telegramVerify();
                receiver::processUpdate();
                logger::write('Update received , lets process it ;)');
            }
            else {
                self::deleteOldLocks();
                self::checkURL();
                self::setCertificate();
                $url = self::setURL();
                self::setWebhook($url);
                lock::set('BPT-HOOK');
                BPT::exit('Done');
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

    protected static function setWebhook(string $url) {
        $res = telegram::setWebhook($url, settings::$certificate, max_connections:settings::$max_connection, allowed_updates : settings::$allowed_updates);
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
}