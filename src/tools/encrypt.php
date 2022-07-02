<?php

namespace BPT\tools;

use BPT\constants\cryptoAction;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\logger;

trait encrypt {
    /**
     * encrypt or decrypt a text with really high security
     *
     * action parameter must be encrypt or decrypt
     *
     * string parameter is your hash(received when use encrypt) or the text you want to encrypt
     *
     * for decrypt , you must have key and iv parameter. you can found them in result of encrypt
     *
     * e.g. => tools::crypto(action: 'decrypt', text: '9LqUf9DSuRRwfo03RnA5Kw==', key: '39aaadf402f9b921b1d44e33ee3b022716a518e97d6a7b55de8231de501b4f34', iv: 'a2e5904a4110169e');
     *
     * e.g. => tools::crypto(cryptoAction::ENCRYPT,'hello world');
     *
     * @param string      $action e.g. => cryptoAction::ENCRYPT | 'encrypt'
     * @param string      $text   e.g. => 'hello world'
     * @param null|string $key    e.g. => Optional, 39aaadf402f9b921b1d44e33ee3b022716a518e97d6a7b55de8231de501b4f34
     * @param null|string $iv     e.g. => Optional, a2e5904a4110169e
     *
     * @return string|bool|array{hash:string, key:string, iv:string}
     * @throws bptException
     */
    public static function crypto (string $action, string $text, string $key = null, string $iv = null): bool|array|string {
        if (extension_loaded('openssl')) {
            if ($action === cryptoAction::ENCRYPT) {
                $key = self::randomString(64);
                $iv = self::randomString();
                $output = base64_encode(openssl_encrypt($text, 'AES-256-CBC', $key, 1, $iv));
                return ['hash' => $output, 'key' => $key, 'iv' => $iv];
            }
            elseif ($action === cryptoAction::DECRYPT) {
                if (empty($key)) {
                    logger::write("tools::crypto function used\nkey parameter is not set",loggerTypes::ERROR);
                    throw new bptException('ARGUMENT_NOT_FOUND_KEY');
                }
                elseif (empty($iv)) {
                    logger::write("tools::crypto function used\niv parameter is not set",loggerTypes::ERROR);
                    throw new bptException('ARGUMENT_NOT_FOUND_IV');
                }
                return openssl_decrypt(base64_decode($text), 'AES-256-CBC', $key, 1, $iv);
            }
            else {
                logger::write("tools::crypto function used\naction is not right, its must be `encode` or `decode`",loggerTypes::WARNING);
                return false;
            }
        }
        else {
            logger::write("tools::crypto function used\nopenssl extension is not found , It may not be installed or enabled",loggerTypes::ERROR);
            throw new bptException('OPENSSL_EXTENSION_MISSING');
        }
    }
}