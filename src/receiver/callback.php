<?php

namespace BPT\receiver;
use BPT\BPT;
use BPT\constants\callbackTypes;
use BPT\constants\codecAction;
use BPT\pay\crypto;
use BPT\settings;
use BPT\tools\tools;

class callback {
    public static function encodeData (array $data): string {
        return tools::codec(codecAction::ENCRYPT, json_encode($data), md5(settings::$token), 'SguQgUvvKRLvmCyq')['hash'];
    }

    public static function decodeData (string $data): array {
        return json_decode(tools::codec(codecAction::DECRYPT, $data, md5(settings::$token), 'SguQgUvvKRLvmCyq'), true);
    }

    public static function process () {
        if (!settings::$handler || settings::$receiver != \BPT\constants\receiver::WEBHOOK || settings::$multi || !(isset($_GET['data']) || isset($_POST['data']))) {
            return false;
        }

        $input = $_GET['data'] ?? $_POST['data'];

        if (!($data = self::decodeData($input))) {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        if ($data['type'] === callbackTypes::CRYPTO) {
            return crypto::callbackProcess($data);
        }

        return false;
    }

    public static function callHandler (string $handler_name, $input) {
        if (method_exists(BPT::$handler, $handler_name)) {
            BPT::$handler->$handler_name($input);
        }
    }
}