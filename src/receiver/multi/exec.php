<?php

namespace BPT\receiver\multi;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\lock;
use BPT\logger;
use BPT\receiver\webhook;
use BPT\settings;
use JetBrains\PhpStorm\ArrayShape;

/**
 * exec class , for multiprocessing with exec method
 */
class exec extends webhook {
    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init(): string|null {
        return self::getUpdate();
    }

    private static function getUpdate (): string|null {
        $up = glob('*.update');
        if (!isset($up[0])) {
            logger::write('not authorized access denied. IP : '. $_SERVER['REMOTE_ADDR'] ?? 'unknown',loggerTypes::WARNING);
            BPT::exit();
        }
        $up = end($up);
        webhook::telegramVerify(explode('-', $up)[0]);
        $update = file_get_contents($up);
        unlink($up);
        return $update;
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function support(): bool {
        return function_exists('exec')
            && !in_array('exec', array_map('trim', explode(', ', ini_get('disable_functions'))))
            && strtolower(ini_get('safe_mode')) != 1;
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function install() {
        $urls = self::setURLS();
        self::create($urls['file']);
        self::setWebhook($urls['url']);
        lock::set('BPT-MULTI-EXEC');
    }

    private static function create($file) {
        file_put_contents('receiver.php', '<?php $BPT = file_get_contents("php://input");$id = json_decode($BPT, true)[\'update_id\'];file_put_contents("{$_SERVER[\'REMOTE_ADDR\']}-$id.update",$BPT);exec("php ' . $file . ' > /dev/null &");');
    }

    #[ArrayShape(['url' => "array|string|string[]", 'file' => "string"])]
    private static function setURLS(): array {
        $base_url = (isset(settings::$certificate) ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return [
            'url'=>str_replace(basename($_SERVER['REQUEST_URI']), 'receiver.php', $base_url),
            'file'=>basename($_SERVER['SCRIPT_NAME'])
        ];
    }
}