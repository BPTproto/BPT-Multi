<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use BPT\types\update;
use stdClass;

class receiver {
    private static array $handlers = [
        'message' => null,
        'callback_query' => null,
        'inline_query' => null,
        'edited_message' => null,
        'something_else' => null
    ];

    protected static function telegramVerify(string $ip = null) {
        if (settings::$telegram_verify) {
            if (!tools::isTelegram($ip ?? $_SERVER['REMOTE_ADDR'] ?? '')) {
                logger::write('not authorized access denied. IP : '. $ip ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown',loggerTypes::WARNING);
                BPT::exit();
            }
        }
    }

    protected static function processUpdate(string|stdClass $update = null) {
        if (!is_object($update)) {
            $update = json_decode($update ?? file_get_contents("php://input"));
            if (!$update) {
                BPT::exit();
            }
        }
        $update = new update($update);
        self::setMessageExtra($update);
        BPT::$update = $update;
        self::processHandler();
    }

    protected static function setMessageExtra (update &$update) {
        if ((isset($update->message) && isset($update->message->text)) || (isset($update->edited_message) && isset($update->edited_message->text))) {
            $type = isset($update->message) ? 'message' : 'edited_message';
            $text = &$update->$type->text;
            if (settings::$security) {
                $text = tools::clearText($text);
            }
            if (str_starts_with($text, '/')) {
                preg_match('/\/([a-zA-Z_0-9]{1,64})(@[a-zA-Z]\w{1,28}bot)?( [\S]{1,64})?/', $text, $result);
                if (isset($result[1])) {
                    $update->$type->commend = $result[1];
                }
                if (isset($result[2])) {
                    $update->$type->commend_username = $result[2];
                }
                if (isset($result[3])) {
                    $update->$type->commend_payload = $result[3];
                }
            }
        }
    }

    private static function processHandler() {
        if (settings::$handler) {
            if (isset(BPT::$update->message)) {
                if (self::handlerExist('message')) {
                    BPT::$handler->message(BPT::$update->message);
                }
            }
            elseif (isset(BPT::$update->callback_query)) {
                if (self::handlerExist('callback_query')) {
                    BPT::$handler->callback_query(BPT::$update->callback_query);
                }
            }
            elseif (isset(BPT::$update->inline_query)) {
                if (self::handlerExist('inline_query')) {
                    BPT::$handler->inline_query(BPT::$update->inline_query);
                }
            }
            elseif (isset(BPT::$update->edited_message)) {
                if (self::handlerExist('edited_message')) {
                    BPT::$handler->edited_message(BPT::$update->edited_message);
                }
            }
            elseif (self::handlerExist('something_else')) {
                BPT::$handler->something_else(BPT::$update);
            }
            else {
                logger::write('Update received but handlers does not set',loggerTypes::WARNING);
            }
        }
    }

    private static function handlerExist(string $handler): bool {
        if (empty(self::$handlers[$handler])) {
            self::$handlers[$handler] = method_exists(BPT::$handler, $handler);
        }
        return self::$handlers[$handler];
    }
}