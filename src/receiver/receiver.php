<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\constants\fields;
use BPT\constants\loggerTypes;
use BPT\database\db;
use BPT\logger;
use BPT\settings;
use BPT\telegram\telegram;
use BPT\tools\tools;
use BPT\types\update;
use stdClass;

/**
 * receiver class , will be used in webhook and getUpdates classes
 */
class receiver {
    private static array $handlers = [
        'message' => null,
        'edited_message' => null,
        'channel_post' => null,
        'edited_channel_post' => null,
        'inline_query' => null,
        'callback_query' => null,
        'my_chat_member' => null,
        'chat_member' => null,
        'chat_join_request' => null,
        'something_else' => null
    ];

    protected static function telegramVerify(string $ip = null): void {
        if (settings::$telegram_verify) {
            $ip = $ip ?? tools::remoteIP();
            if (!tools::isTelegram($ip)) {
                if (!callback::process()) {
                    logger::write('not authorized access denied. IP : '. $ip ?? 'unknown',loggerTypes::WARNING);
                    BPT::exit();
                }
                die('callback handler stole the process :(');
            }
        }
    }

    protected static function processUpdate(string|stdClass|update $update = null): void {
        if (!is_object($update)) {
            $update = json_decode($update ?? file_get_contents('php://input'));
            if (!$update) {
                BPT::exit();
            }
        }

        if (settings::$ignore_updates_older_then > 0) {
            if (time() - settings::$ignore_updates_older_then > telegram::catchFields(fields::UPDATE_DATE)) {
                logger::write('Update is old, Ignored.');
                return;
            }
        }

        if (settings::$use_types_classes && !is_a($update,'update')) {
            $update = new update($update);
        }

        self::setMessageExtra($update);
        BPT::$update = $update;
        db::process();
        self::processHandler();
        db::save();
    }

    protected static function setMessageExtra (stdClass|update &$update): void {
        if (!isset($update->message->text) && !isset($update->edited_message->text)) {
            return;
        }
        $type = isset($update->message) ? 'message' : 'edited_message';
        $text = &$update->{$type}->text;
        if (settings::$security) {
            $text = tools::clearText($text);
        }
        if (str_starts_with($text, '/')) {
            preg_match('/\/([a-zA-Z_0-9]{1,64})(@[a-zA-Z]\w{1,28}bot)?( [\S]{1,64})?/', $text, $result);
            if (isset($result[1])) {
                $update->{$type}->command = $result[1];
            }
            if (isset($result[2])) {
                $update->{$type}->command_username = $result[2];
            }
            if (isset($result[3])) {
                $update->{$type}->command_payload = trim($result[3]);
            }
        }
    }

    private static function processHandler(): void {
        if (!settings::$handler) {
            return;
        }
        if (isset(BPT::$update->message)) {
            if (self::handlerExist('message')) {
                BPT::$handler->message(BPT::$update->message);
            }
        }
        elseif (isset(BPT::$update->edited_message)) {
            if (self::handlerExist('edited_message')) {
                BPT::$handler->edited_message(BPT::$update->edited_message);
            }
        }
        elseif (isset(BPT::$update->channel_post)) {
            if (self::handlerExist('channel_post')) {
                BPT::$handler->channel_post(BPT::$update->channel_post);
            }
        }
        elseif (isset(BPT::$update->edited_channel_post)) {
            if (self::handlerExist('edited_channel_post')) {
                BPT::$handler->edited_channel_post(BPT::$update->edited_channel_post);
            }
        }
        elseif (isset(BPT::$update->inline_query)) {
            if (self::handlerExist('inline_query')) {
                BPT::$handler->inline_query(BPT::$update->inline_query);
            }
        }
        elseif (isset(BPT::$update->callback_query)) {
            if (self::handlerExist('callback_query')) {
                BPT::$handler->callback_query(BPT::$update->callback_query);
            }
        }
        elseif (isset(BPT::$update->my_chat_member)) {
            if (self::handlerExist('my_chat_member')) {
                BPT::$handler->my_chat_member(BPT::$update->my_chat_member);
            }
        }
        elseif (isset(BPT::$update->chat_member)) {
            if (self::handlerExist('chat_member')) {
                BPT::$handler->chat_member(BPT::$update->chat_member);
            }
        }
        elseif (isset(BPT::$update->chat_join_request)) {
            if (self::handlerExist('chat_join_request')) {
                BPT::$handler->chat_join_request(BPT::$update->chat_join_request);
            }
        }
        elseif (self::handlerExist('something_else')) {
            BPT::$handler->something_else(BPT::$update);
        }
        else {
            logger::write('Update received but handlers are not set',loggerTypes::WARNING);
        }
    }

    private static function handlerExist(string $handler): bool {
        if (empty(self::$handlers[$handler])) {
            self::$handlers[$handler] = method_exists(BPT::$handler, $handler);
        }
        return self::$handlers[$handler];
    }
}