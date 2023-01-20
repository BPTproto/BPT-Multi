<?php

namespace BPT\telegram\request;

use BPT\constants\loggerTypes;
use BPT\constants\receiver;
use BPT\exception\bptException;
use BPT\logger;
use BPT\settings;

/**
 * answer class , part of request class for handling request based on answering to webhook directly
 */
class answer {
    private static bool $is_answered = false;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init(string $method,array $data): bool {
        self::checkWebhook();
        self::sieveData($data);
        self::$is_answered = true;
        $data['method'] = $method;
        $payload = json_encode($data);
        header('Content-Type: application/json;Content-Length: ' . strlen($payload));
        echo $payload;
        return true;
    }

    public static function isAnswered (): bool {
        return self::$is_answered;
    }

    private static function checkWebhook(): void {
        if(settings::$receiver === receiver::GETUPDATES) {
            logger::write('Answer mode only work when receiver is webhook',loggerTypes::ERROR);
            throw new bptException('ANSWER_MODE_GETUPDATES');
        }
        if(settings::$multi) {
            logger::write('You can not use answer mode when multi setting is on',loggerTypes::ERROR);
            throw new bptException('ANSWER_MODE_MULTI');
        }
    }

    private static function sieveData(array &$data): void {
        unset($data['token'],$data['forgot'],$data['return_array']);

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
