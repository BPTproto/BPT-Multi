<?php

namespace BPT\receiver;

use BPT\api\telegram;
use BPT\BPT;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use BPT\types\update;
use CURLFile;

class webhook {
    public static function init () {
        if (settings::$multi) {
            multi::init();
        }
        else {
            if (lock::exist('BPT-HOOK')) {
                self::telegramVerify();
                BPT::$update = self::processUpdate();
                logger::write('Update received , lets process it ;)');
            }
            else {
                self::deleteOldLocks();
                self::setWebhook();
            }
        }
    }

    private static function telegramVerify() {
        if (settings::$telegram_verify) {
            if (!tools::isTelegram($_SERVER['REMOTE_ADDR'])) {
                logger::write('not authorized access denied. IP : '.$_SERVER['REMOTE_ADDR'],'error');
                BPT::exit();
            }
        }
    }

    private static function processUpdate(): update {
        $update = json_decode(file_get_contents("php://input"));
        if (!$update) {
            BPT::exit();
        }
        $update = new update($update);
        self::setMessageExtra($update);
        return $update;
    }

    private static function deleteOldLocks() {
        if (lock::exist('BPT-MULTI')) {
            lock::delete('BPT-MULTI');
        }
        if (lock::exist('BPT-MULTI-2')) {
            lock::delete('BPT-MULTI-2');
        }
        if (lock::exist('getUpdate')) {
            lock::delete('getUpdate');
        }
    }

    private static function setWebhook() {
        if (isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI'])) {
            self::setCertificate();
            $url = (isset(settings::$certificate) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            $res = telegram::setWebhook($url, settings::$certificate, max_connections:settings::$max_connection, allowed_updates : settings::$allowed_updates);
            if ($res->ok) {
                lock::set('BPT');
                logger::write('Webhook was set successfully','info');
                BPT::exit('Done');
            }
            else {
                logger::write("There is some problem happened , telegram response : \n".json_encode($res),'error');
                BPT::exit(print_r($res,true));
            }
        }
        else {
            logger::write('For using webhook receiver , you should open this file in your webserver(by domain)');
        }
    }

    private static function setCertificate() {
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

    private static function setMessageExtra(update &$update) {
        if((isset($update->message) && isset($update->message->text)) || (isset($update->edited_message) && isset($update->edited_message->text))){
            if (isset($update->message)) $type = 'message';
            else $type = 'edited_message';

            $text = &$update->$type->text;
            if (settings::$security){
                $text = tools::clearText($text);
            }
            if (str_starts_with($text, '/')){
                preg_match('/\/([a-zA-Z_0-9]{1,64})(@[a-zA-Z]\w{1,28}bot)?( [\S]{1,64})?/',$text,$result);
                if (!empty($result[1])){
                    $update->$type->commend = $result[1];
                }
                if (!empty($result[2])){
                    $update->$type->commend_username = $result[2];
                }
                if (!empty($result[3])){
                    $update->$type->commend_payload = $result[3];
                }
            }
        }
    }
}