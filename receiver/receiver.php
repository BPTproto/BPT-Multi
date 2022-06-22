<?php

namespace BPT\receiver;

use BPT\BPT;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use BPT\types\update;

class receiver {
    protected static function telegramVerify(string $ip = null) {
        if (settings::$telegram_verify) {
            if (!tools::isTelegram($ip ?? $_SERVER['REMOTE_ADDR'] ?? '')) {
                logger::write('not authorized access denied. IP : '. $ip ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown','error');
                BPT::exit();
            }
        }
    }

    protected static function processUpdate(string $json = null): update {
        $update = json_decode($json ?? file_get_contents("php://input"));
        if (!$update) {
            BPT::exit();
        }
        $update = new update($update);
        self::setMessageExtra($update);
        return $update;
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
                $update->$type->commend = $result[1] ?? null;
                $update->$type->commend_username = $result[2] ?? null;
                $update->$type->commend_payload = $result[3] ?? null;
            }
        }
    }
}