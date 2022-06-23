<?php

namespace BPT\api\request;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\constants\receiver;
use BPT\logger;
use BPT\settings;

class answer {
    private static bool $is_answered = false;

    public static function init(string $method,array $data) {
        self::checkAnswered();
        self::checkWebhook();
        self::deleteAdditionalData($data);
        self::$is_answered = true;
        $data['method'] = $method;
        $payload = json_encode($data);
        header('Content-Type: application/json;Content-Length: ' . strlen($payload));
        echo $payload;
        return true;
    }

    private static function checkAnswered() {
        if (self::$is_answered) {
            logger::write('You can use answer mode only once for each webhook update , You already did it!',loggerTypes::ERROR);
            BPT::exit();
        }
    }

    private static function checkWebhook() {
        if(settings::$receiver === receiver::GETUPDATES) {
            logger::write('Answer mode only work when receiver is webhook',loggerTypes::ERROR);
            BPT::exit();
        }

        if(settings::$multi) {
            logger::write('You can not use answer mode when multi setting is on',loggerTypes::ERROR);
            BPT::exit();
        }
    }

    private static function deleteAdditionalData(array &$data) {
        if (isset($data['token'])) {
            unset($data['token']);
        }
        if (isset($data['forgot'])) {
            unset($data['forgot']);
        }
        if (isset($data['return_array'])) {
            unset($data['return_array']);
        }
        foreach ($data as $key=>&$value){
            if (!isset($value)){
                unset($data[$key]);
                continue;
            }
            if (is_array($value) || (is_object($value) && !is_a($value,'CURLFile'))){
                $value = json_encode($value);
            }
        }
    }
}