<?php

namespace BPT\telegram\request;

use BPT\constants\loggerTypes;
use BPT\logger;
use BPT\settings;
use CurlHandle;
use JetBrains\PhpStorm\ArrayShape;

/**
 * curl class , part of request class for handling request based on curl
 */
class curl {
    private static CurlHandle $curl_handler;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init(string $method,array $data) {
        $info = self::getInfo($data);
        $data = $info['data'];
        $handler = $info['handler'];
        self::setTimeout($data,$handler,$method);
        self::setData($data);
        $data['method'] = $method;
        curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($handler);
        if (curl_errno($handler)) {
            logger::write(curl_error($handler),loggerTypes::WARNING);
        }
        if ($info['token'] != settings::$token) {
            curl_close($handler);
        }
        return json_decode($result);
    }

    #[ArrayShape(['data'    => "array", 'token'   => "mixed|string", 'handler' => "\CurlHandle|false|CurlHandle"])]
    private static function getInfo(array $data): array {
        if (isset($data['token']) && $data['token'] !== settings::$token) {
            $token = $data['token'];
            unset($data['token']);
            $curl_handler = curl_init(settings::$base_url."/bot$token/");
            curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, false);
        }
        else{
            $token = settings::$token;
            if (!isset(self::$curl_handler)){
                self::$curl_handler = curl_init(settings::$base_url."/bot$token/");
                curl_setopt(self::$curl_handler, CURLOPT_RETURNTRANSFER, true);
                curl_setopt(self::$curl_handler, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt(self::$curl_handler, CURLOPT_TCP_KEEPALIVE, 1);
            }
            $curl_handler = self::$curl_handler;
        }

        return [
            'data'    => $data,
            'token'   => $token,
            'handler' => $curl_handler
        ];
    }

    private static function setTimeout(array &$data , CurlHandle $curl_handler,string $method): void {
        if (isset($data['forgot'])) {
            curl_setopt($curl_handler, CURLOPT_TIMEOUT_MS, settings::$forgot_time);
            unset($data['forgot']);
        }
        elseif ($method === 'getUpdates' || $method === 'setWebhook'){
            curl_setopt($curl_handler, CURLOPT_TIMEOUT_MS, 5000);
        }
        else{
            curl_setopt($curl_handler, CURLOPT_TIMEOUT_MS, settings::$base_timeout);
        }
    }

    private static function setData(array &$data): void {
        foreach ($data as &$value){
            if (is_array($value) || (is_object($value) && !is_a($value,'CURLFile'))){
                $value = json_encode($value);
            }
        }
    }
}