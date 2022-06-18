<?php

namespace BPT;

use BPT\api\telegram;
use BPT\constants\parseMode;

class tools {
    /**
     * Check the given username format
     *
     * e.g. => tools::isUsername('BPT_CH');
     *
     * e.g. => tools::isUsername(username: 'BPT_CH');
     *
     * @param string $username Your text to be check is username or not e.g. : 'BPT_CH' | '@BPT_CH'
     * @return bool
     */
    public static function isUsername(string $username): bool {
        $length = strlen($username);
        return strpos($username, '__') === false && $length >= 5 && $length <= 33 && preg_match('/^@?([a-zA-Z])(\w{4,31})$/', $username);
    }

    /**
     * Check given IP is in the given IP range or not
     *
     * e.g. => tools::ipInRange('192.168.1.1','149.154.160.0/20');
     *
     * e.g. => tools::ipInRange(ip: '192.168.1.1',range: '149.154.160.0/20');
     *
     * @param string $ip Your ip
     * @param string $range Your range ip for check , if you didn't specify the block , it will be 32
     * @return bool
     */
    public static function ipInRange (string $ip, string $range): bool {
        if (!str_contains($range, '/')) {
            $range .= '/32';
        }
        $range_full = explode('/', $range, 2);
        $netmask_decimal = ~(pow(2, (32 - $range_full[1])) - 1);
        return (ip2long($ip) & $netmask_decimal) == (ip2long($range_full[0]) & $netmask_decimal);
    }

    /**
     * Check the given IP is from telegram or not
     *
     * e.g. => tools::isTelegram('192.168.1.1');
     *
     * e.g. => tools::isTelegram(ip: '192.168.1.1');
     *
     * @param string $ip     Your ip to be check is telegram or not e.g. '192.168.1.1'
     * @return bool
     */
    public static function isTelegram (string $ip): bool {
        return self::ipInRange($ip, '149.154.160.0/20') || self::ipInRange($ip, '91.108.4.0/22');
    }

    /**
     * Check the given IP is from CloudFlare or not
     *
     * e.g. => tools::isCloudFlare('192.168.1.1');
     *
     * e.g. =>tools::isCloudFlare(ip: '192.168.1.1');
     *
     * @param string $ip Your ip to be check is CloudFlare or not e.g. '192.168.1.1'
     * @return bool
     */
    public static function isCloudFlare (string $ip): bool {
        $cf_ips = ['173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/12', '172.64.0.0/13', '131.0.72.0/22'];
        foreach ($cf_ips as $cf_ip) {
            if (self::ipInRange($ip,$cf_ip)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check the given token format
     *
     * if you want to verify token with telegram , you should set verify parameter => true
     *
     * in that case , if token was right , you will receive getMe result , otherwise you will receive false
     *
     * verify parameter has default value => false
     *
     * e.g. => tools::isToken('123123123:abcabcabcabc');
     * @param string $token your token e.g. => '123123123:abcabcabcabc'
     * @param bool $verify check token with telegram or not
     * @return bool|array return array when verify is active and token is true array of telegram getMe result
     */
    public static function isToken (string $token, bool $verify = false): bool|array {
        if (preg_match('/^(\d{8,10}):[\w\-]{35}$/', $token)) {
            if ($verify){
                $res = telegram::me($token);
                if ($res['ok']) {
                    return $res['result'];
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Generate random string
     *
     * you can use this method without any input
     *
     * length parameter have default value => 16
     *
     * characters parameter have default value => aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ
     *
     * e.g. => tools::randomString();
     *
     * e.g. => tools::randomString(16,'abcdefg');
     *
     * e.g. => tools::randomString(length: 16,characters: 'abcdefg');
     *
     * @param int $length e.g. => 16
     * @param string $characters e.g. => 'abcdefg'
     * @return string
     */
    public static function randomString (int $length = 16, string $characters = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ'): string {
        $rand_string = '';
        $char_len = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $rand_string .= $characters[rand(0, $char_len)];
        }
        return $rand_string;
    }

    /**
     * Escape text for different parse_modes
     *
     * type parameter can be : `MarkdownV2` , `Markdown` , `HTML` , default : `parseMode::HTML`(`HTML`)
     *
     * e.g. => tools::modeEscape('hello men! *I* Have nothing anymore');
     *
     * e.g. => tools::modeEscape(text: 'hello men! *I* Have nothing anymore');
     *
     * @param string $text Your text e.g. => 'hello men! *I* Have nothing anymore'
     * @param string $mode Your selected mode e.g. => `parseMode::HTML` | `HTML`
     * @return string|false return false when mode is incorrect
     */
    public static function modeEscape (string $text, string $mode = parseMode::HTML): string|false {
        return match ($mode) {
            parseMode::HTML => str_replace(['&', '<', '>',], ["&amp;", "&lt;", "&gt;",], $text),
            parseMode::MARKDOWN => str_replace(['\\', '_', '*', '`', '['], ['\\\\', '\_', '\*', '\`', '\[',], $text),
            parseMode::MARKDOWNV2 => str_replace(
                ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!', '\\'],
                ['\_', '\*', '\[', '\]', '\(', '\)', '\~', '\`', '\>', '\#', '\+', '\-', '\=', '\|', '\{', '\}', '\.', '\!', '\\\\'],
                $text),
            default => false
        };
    }

    /**
     * Convert byte to symbolic size like 2.98 MB
     *
     * Supp
     *
     * You could set `precision` to configure decimals after number(2 for 2.98 and 3 for 2.987)
     *
     * `precision` parameter have default value => 2
     *
     * e.g. => tools::byteFormat(123456789);
     *
     * e.g. => tools::byteFormat(byte: 123456789);
     *
     * @param int $byte e.g. => 29123452912
     * @param int $precision e.g. => 2
     * @return string
     */
    public static function byteFormat (int $byte, int $precision = 2): string {
        $rate_counter = 0;

        while ($byte > 1024){
            $byte /= 1024;
            $rate_counter++;
        }

        if ($rate_counter !== 0) {
            $byte = round($byte, $precision);
        }

        return $byte . ' ' . ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'][$rate_counter];
    }

    /**
     * receive size from path(can be url or file path)
     *
     * if format parameter has true value , the returned size converted to symbolic format
     *
     * format parameter have default value => true
     *
     * NOTE : some url will not return real size!
     *
     * e.g. => tools::size('xFile.zip');
     *
     * e.g. => tools::size(path: 'xFile.zip');
     *
     * @param string $path e.g. => 'xFile.zip'
     * @param bool $format if you set this true , you will receive symbolic string like 2.76MB
     * @return string|int|false string for formatted data , int for normal data , false when size can not be found(file not found or ...)
     */
    public static function size (string $path, bool $format = true): string|int|false {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $ch = curl_init($path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            curl_close($ch);
        }
        else {
            $size = file_exists($path) ? filesize($path) : false;
        }

        if (isset($size) && is_numeric($size)) {
            return $format ? tools::rateConverter($size) : $size;
        }
        else return false;
    }

    public static function error2trace (\Throwable $error,$seen = null): string {
        $starter = $seen ? 'Caused by: ' : '';
        $result = [];
        if (!$seen) $seen = [];
        $trace = $error->getTrace();
        $prev = $error->getPrevious();
        //$result[] = sprintf('%s%s: %s', $starter, get_class($error), $error->getMessage());
        $file = $error->getFile();
        $line = $error->getLine();

        while (true){
            $current = "$file:$line";
            if (is_array($seen) && in_array($current, $seen)) {
                $result[] = sprintf(' ... %d more', count($trace) + 1);
                break;
            }
            $result[] = sprintf(' at %s%s%s(%s%s%s)',
                count($trace) && array_key_exists('class', $trace[0]) ? $trace[0]['class'] : '',
                count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? (isset($trace[0]['type']) ? $trace[0]['type'] : '.') : '',
                count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                $file,
                $line === null ? '' : ':',
                $line === null ? '' : $line);
            if (is_array($seen)) {
                $seen[] = "$file:$line";
            }
            if (!count($trace)) {
                break;
            }
            $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
            $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
            array_shift($trace);
        }

        $result = join("\n", $result);
        if ($prev) {
            $result .= "\n" . self::error2trace($prev, $seen);
        }
        return $result;
    }
}