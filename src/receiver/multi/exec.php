<?php

namespace BPT\receiver\multi;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\lock;
use BPT\logger;
use BPT\receiver\webhook;
use BPT\settings;
use JetBrains\PhpStorm\ArrayShape;

class exec extends webhook {
    public static function init(): string|null {
        return self::getUpdate();
    }

    private static function getUpdate (): string|null {
        $up = glob('*.update');
        if (isset($up[0])) {
            $up = end($up);
            $ip = explode('-', $up)[0];
            webhook::telegramVerify($ip);
            $update = file_get_contents($up);
            unlink($up);
            return $update;
        }
        else {
            logger::write('not authorized access denied. IP : '. $_SERVER['REMOTE_ADDR'] ?? 'unknown',loggerTypes::WARNING);
            BPT::exit();
        }
    }

    public static function support(): bool {
        return function_exists('exec')
            && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))))
            && strtolower(ini_get('safe_mode')) != 1;
    }

    public static function install() {
        $urls = self::setURLS();
        $url = $urls['url'];
        self::create($urls['file']);
        self::setWebhook($url);
        lock::set('BPT-MULTI-EXEC');
    }

    private static function create($file) {
        file_put_contents('receiver.php', '<?php $BPT = file_get_contents("php://input");$id = json_decode($BPT, true)[\'update_id\'];file_put_contents("{$_SERVER[\'REMOTE_ADDR\']}-$id.update",$BPT);exec("php ' . $file . ' > /dev/null &");');
    }

    private static function setURLS(): array {
        $base_url = (isset(settings::$certificate) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return [
            'url'=>str_replace(basename($_SERVER['REQUEST_URI']), 'receiver.php', $base_url),
            'file'=>basename($_SERVER['SCRIPT_NAME'])
        ];
    }
}