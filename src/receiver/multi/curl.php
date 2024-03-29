<?php

namespace BPT\receiver\multi;

use BPT\BPT;
use BPT\constants\loggerTypes;
use BPT\lock;
use BPT\logger;
use BPT\receiver\webhook;
use JetBrains\PhpStorm\ArrayShape;

/**
 * curl class , for multiprocessing with curl tricks
 */
class curl extends webhook {
    public static function init (): string|null {
        if (!self::checkIP()) {
            logger::write('not authorized access denied. IP : '. $_SERVER['REMOTE_ADDR'] ?? 'unknown',loggerTypes::WARNING);
            BPT::exit();
        }
        return self::getUpdate();
    }

    private static function checkIP(): bool {
        return $_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR'];
    }

    private static function getUpdate (): string {
        $input = json_decode(file_get_contents('php://input'), true);
        webhook::telegramVerify($input['ip']);
        return $input['update'];
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function install() {
        $urls = self::setURLS();
        $file = $urls['file'];
        self::create($file, self::getTimeout($file));
        self::setWebhook($urls['url']);
        lock::set('BPT-MULTI-CURL');
    }

    private static function getTimeout($url): float|int {
        $times = [];
        for ($i = 0; $i < 10; $i ++) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [CURLOPT_POSTFIELDS => json_encode([]), CURLOPT_TIMEOUT_MS => 100, CURLOPT_NOBODY => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_CONNECTTIMEOUT_MS => 100, CURLOPT_HTTPHEADER => ['accept: application/json', 'content-type: application/json']]);
            $start = microtime(true);
            curl_exec($ch);
            $times[] = ((microtime(true) - $start) * 1000);
        }
        $timeout = round(array_sum($times) / count($times));
        return $timeout > 50 ? $timeout + 10 : 50;
    }

    private static function create($file,$timeout) {
        file_put_contents('receiver.php', '<?php http_response_code(200);ignore_user_abort();$ch = curl_init(\'' . $file . '\');curl_setopt_array($ch, [CURLOPT_POSTFIELDS => json_encode([\'update\'=>file_get_contents(\'php://input\'),\'ip\'=>$_SERVER[\'REMOTE_ADDR\']]), CURLOPT_TIMEOUT_MS => ' . $timeout . ', CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_CONNECTTIMEOUT_MS => ' . $timeout . ', CURLOPT_HTTPHEADER => [\'accept: application/json\', \'content-type: application/json\']]);curl_exec($ch);curl_close($ch);?>');
    }

    #[ArrayShape(['url' => 'array|string|string[]', 'file' => 'array|string|string[]'])]
    private static function setURLS(): array {
        $base_url = self::setURL();
        $file = basename($_SERVER['REQUEST_URI']);
        return [
            'url'=>str_replace($file, 'receiver.php', $base_url),
            'file'=>str_replace($file, basename($_SERVER['SCRIPT_NAME']), $base_url)
        ];
    }
}