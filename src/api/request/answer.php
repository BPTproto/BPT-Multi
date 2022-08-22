<?php

namespace BPT\api\request;

use BPT\constants\loggerTypes;
use BPT\constants\receiver;
use BPT\exception\bptException;
use BPT\logger;
use BPT\settings;

class answer {
    private static bool $is_answered = false;

    public static function init(string $method,array $data): bool {
        self::checkAnswered();
        self::checkWebhook();
        self::sieveData($data);
        self::$is_answered = true;
        $data['method'] = $method;
        $payload = json_encode($data);
        header('Content-Type: application/json;Content-Length: ' . strlen($payload));
        echo $payload;
        return true;
    }

    private static function checkAnswered(): void {
        if (self::$is_answered) {
            logger::write('You can use answer mode only once for each webhook update , You already did it!',loggerTypes::ERROR);
            throw new bptException('ANSWER_MODE_USED');
        }
    }

    private static function checkWebhook(): void {
        if(settings::$receiver === receiver::GETUPDATES) {
            logger::write('Answer mode only work when receiver is webhook',loggerTypes::ERROR);
            throw new bptException('ANSWER_MODE_GETUPDATES');
        }
        elseif(settings::$multi) {
            logger::write('You can not use answer mode when multi setting is on',loggerTypes::ERROR);
            throw new bptException('ANSWER_MODE_MULTI');
        }
    }

    private static function sieveData(array &$data): void {
        unset($data['token']);
        unset($data['forgot']);
        unset($data['return_array']);

        foreach ($data as $key=>&$value){
            if (!isset($value)){
                unset($data[$key]);
            }
            elseif (is_array($value) || is_object($value)){
                $value = json_encode($value);
            }
        }
    }
}
