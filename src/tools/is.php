<?php

namespace BPT\tools;

use BPT\api\request;
use BPT\api\telegram;
use BPT\constants\chatMemberStatus;
use BPT\tools;
use BPT\types\user;

trait is {
    /**
     * Check the given username format
     *
     * e.g. => tools::isUsername('BPT_CH');
     *
     * e.g. => tools::isUsername(username: 'BPT_CH');
     *
     * @param string $username Your text to be check is it username or not , @ is not needed
     *
     * @return bool
     */
    public static function isUsername (string $username): bool {
        $length = strlen($username);
        return !str_contains($username, '__') && $length >= 5 && $length <= 33 && preg_match('/^@?([a-zA-Z])(\w{4,31})$/', $username);
    }

    /**
     * Check given IP is in the given IP range or not
     *
     * e.g. => tools::ipInRange('192.168.1.1','149.154.160.0/20');
     *
     * e.g. => tools::ipInRange(ip: '192.168.1.1',range: '149.154.160.0/20');
     *
     * @param string $ip    Your ip
     * @param string $range Your range ip for check , if you didn't specify the block , it will be 32
     *
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
     * @param string $ip Your ip to be check is telegram or not e.g. '192.168.1.1'
     *
     * @return bool
     */
    public static function isTelegram (string $ip): bool {
        return tools::ipInRange($ip, '149.154.160.0/20') || tools::ipInRange($ip, '91.108.4.0/22');
    }

    /**
     * Check the given IP is from CloudFlare or not
     *
     * e.g. => tools::isCloudFlare('192.168.1.1');
     *
     * e.g. =>tools::isCloudFlare(ip: '192.168.1.1');
     *
     * @param string $ip Your ip to be check is CloudFlare or not
     *
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
     * if you want to verify token with telegram , you should set `verify` parameter => true.
     * in that case , if token was right , you will receive getMe result , otherwise you will receive false
     *
     * e.g. => tools::isToken('123123123:abcabcabcabc');
     *
     * @param string $token  your token e.g. => '123123123:abcabcabcabc'
     * @param bool   $verify check token with telegram or not
     *
     * @return bool|user return array when verify is active and token is true array of telegram getMe result
     */
    public static function isToken (string $token, bool $verify = false): bool|user {
        if (preg_match('/^(\d{8,10}):[\w\-]{35}$/', $token)) {
            if ($verify){
                $res = telegram::me($token);
                if (telegram::$status) {
                    return $res;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * check user joined in channels or not
     *
     * this method only return true or false, if user join in all channels true, and if user not joined in one channel false
     *
     * this method does not care about not founded channel and count them as joined channel
     *
     * ids parameter can be array for multi channels or can be string for one channel
     *
     * NOTE : each channel will decrease speed a little(because of request count)
     *
     * e.g. => tools::isJoined('BPT_CH','442109602');
     *
     * e.g. => tools::isJoined(['BPT_CH','-1005465465454']);
     *
     * @param array|string|int $ids     could be username or id, you can pass multi or single id
     * @param int|null         $user_id if not set , will generate by request::catchFields method
     *
     * @return bool
     */
    public static function isJoined (array|string|int $ids , int|null $user_id = null): bool {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $user_id = $user_id ?? request::catchFields('user_id');

        foreach ($ids as $id) {
            $check = telegram::getChatMember($id,$user_id);
            if (telegram::$status) {
                $check = $check->status;
                if ($check === chatMemberStatus::LEFT || $check === chatMemberStatus::KICKED) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * check user joined in channels or not
     *
     * ids parameter can be array for multi channels or can be string for one channel
     *
     * NOTE : each channel will decrease speed a little(because of request count)
     *
     * e.g. => tools::joinChecker('BPT_CH','442109602');
     *
     * e.g. => tools::joinChecker(['BPT_CH','-1005465465454']);
     *
     * @param array|string|int $ids     could be username or id, you can pass multi or single id
     * @param int|null         $user_id if not set , will generate by request::catchFields method
     *
     * @return array keys will be id and values will be bool(null for not founded ids)
     */
    public static function joinChecker (array|string|int $ids , int|null $user_id = null): array {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $user_id = $user_id ?? request::catchFields('user_id');

        $result = [];
        foreach ($ids as $id) {
            $check = telegram::getChatMember($id,$user_id);
            if (telegram::$status) {
                $check = $check->status;
                $result[$id] = $check !== chatMemberStatus::LEFT && $check !== chatMemberStatus::KICKED;
            }
            else $result[$id] = null;
        }
        return $result;
    }

    /**
     * check is it short encoded or not
     *
     * e.g. => tools::shortEncode(123456789);
     *
     * @param string $text
     *
     * @return bool
     */
    public static function isShorted(string $text): bool{
        return preg_match('/^[a-zA-Z0-9]+$/',$text);
    }
}