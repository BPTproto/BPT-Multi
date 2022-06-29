<?php

namespace BPT;

use BPT\api\request;
use BPT\api\telegram;
use BPT\constants\chatMemberStatus;
use BPT\constants\cryptoAction;
use BPT\constants\loggerTypes;
use BPT\constants\parseMode;
use BPT\constants\pollType;
use BPT\exception\bptException;
use BPT\types\inlineKeyboardButton;
use BPT\types\inlineKeyboardMarkup;
use BPT\types\keyboardButton;
use BPT\types\keyboardButtonPollType;
use BPT\types\replyKeyboardMarkup;
use BPT\types\webAppInfo;
use DateTime;
use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class tools {
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
        return self::ipInRange($ip, '149.154.160.0/20') || self::ipInRange($ip, '91.108.4.0/22');
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
     * @return bool|types\user return array when verify is active and token is true array of telegram getMe result
     */
    public static function isToken (string $token, bool $verify = false): bool|types\user {
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
     * Generate random string
     *
     * e.g. => tools::randomString();
     *
     * e.g. => tools::randomString(16,'abcdefg');
     *
     * e.g. => tools::randomString(length: 16,characters: 'abcdefg');
     *
     * @param int    $length     length of generated string
     * @param string $characters string constructor characters
     *
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
     * mode parameter can be : `MarkdownV2` , `Markdown` , `HTML` , default : `parseMode::HTML`(`HTML`)
     *
     * e.g. => tools::modeEscape('hello men! *I* Have nothing anymore');
     *
     * e.g. => tools::modeEscape(text: 'hello men! *I* Have nothing anymore');
     *
     * @param string $text Your text e.g. => 'hello men! *I* Have nothing anymore'
     * @param string $mode Your selected mode e.g. => `parseMode::HTML` | `HTML`
     *
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
     * You could set `precision` to configure decimals after number(2 for 2.98 and 3 for 2.987)
     *
     * e.g. => tools::byteFormat(123456789);
     *
     * e.g. => tools::byteFormat(byte: 123456789);
     *
     * @param int $byte      size in byte
     * @param int $precision decimal precision
     *
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
     * NOTE : some url will not return real size!
     *
     * e.g. => tools::size('xFile.zip');
     *
     * e.g. => tools::size(path: 'xFile.zip');
     *
     * @param string $path   file path, could be url
     * @param bool   $format if you set this true , you will receive symbolic string like 2.76MB for return
     *
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
            return $format ? tools::byteFormat($size) : $size;
        }
        else return false;
    }

    /**
     * Delete a folder or file if exist
     *
     * e.g. => tools::delete(path: 'xfolder/yfolder');
     *
     * e.g. => tools::delete('xfolder/yfolder',false);
     *
     * @param string $path folder or file path
     * @param bool   $sub  set true for removing subFiles too, if folder has subFiles and this set to false , you will receive error
     *
     * @return bool
     * @throws bptException
     */
    public static function delete (string $path, bool $sub = true): bool {
        if (is_dir($path)) {
            if (count(scandir($path)) > 2) {
                if ($sub) {
                    $it = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $file) {
                        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
                    }
                    return rmdir($path);
                }
                else {
                    logger::write("tools::delete function used\ndelete function cannot delete folder because its have subFiles and sub parameter haven't true value",loggerTypes::ERROR);
                    throw new bptException('DELETE_FOLDER_HAS_SUB');
                }
            }
            else return rmdir($path);
        }
        else return unlink($path);
    }

    /**
     * Show time different in array format
     *
     * Its calculated different between given time and now
     *
     * e.g. => tools::time2string(datetime: 1636913656);
     *
     * e.g. => tools::time2string(time());
     *
     * @param int|string $target_time your chosen time for compare with base_time, could be timestamp or could be a string like `next sunday`
     * @param int|string|null $base_time base time, could be timestamp or could be a string like `next sunday`, set null for current time
     *
     * @return array{status: string,year: string,month: string,day: string,hour: string,minute: string,second: string}
     * @throws Exception
     */
    public static function timeDiff (int|string $target_time, int|string|null $base_time = null): array {
        if (empty($base_time)) {
            $base_time = '@'.time();
        }
        $base_time = new DateTime($base_time);
        $target_time = new DateTime(is_numeric($target_time) ? '@' . $target_time : $target_time . ' +00:00');

        $status = $base_time < $target_time ? 'later' : 'ago';
        $diff = $base_time->diff($target_time);

        $string = ['year' => 'y', 'month' => 'm', 'day' => 'd', 'hour' => 'h', 'minute' => 'i', 'second' => 's'];
        foreach ($string as $k => &$v) {
            if ($diff->$v) {
                $v = $diff->$v;
            }
            else unset($string[$k]);
        }
        $string['status'] = $status;

        return count($string) > 1 ? $string : ['status' => 'now'];
    }

    /**
     * Clear text and make it safer to use
     *
     * e.g. => tools::clearText(text: 'asdasdasdas');
     *
     * e.g. => tools::clearText($message->text);
     *
     * @param string $text your text to be cleaned
     *
     * @return string
     */
    public static function clearText(string $text): string {
        return htmlentities(strip_tags(htmlspecialchars(stripslashes(trim($text)))));
    }

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
     * @return array|string|bool
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
                if (empty($iv)) {
                    logger::write("tools::crypto function used\niv parameter is not set",loggerTypes::ERROR);
                    throw new bptException('ARGUMENT_NOT_FOUND_IV');
                }
                return openssl_decrypt(base64_decode($text), 'AES-256-CBC', $key, 1, $iv);
            }
            else {
                logger::write("tools::crypto function used\naction is not right, its must be `encode` or `decode`");
                return false;
            }
        }
        else {
            logger::write("tools::crypto function used\nopenssl extension is not found , It may not be installed or enabled",loggerTypes::ERROR);
            throw new bptException('OPENSSL_EXTENSION_MISSING');
        }
    }

    /**
     * convert all files in selected path to zip and then save it in dest path
     *
     * e.g. => tools::zip('xFolder','yFolder/xFile.zip',false,true);
     *
     * @param string $path        your file or folder to be zipped
     * @param string $destination destination path for create file
     * @param bool   $self        set true for adding main folder to zip file
     * @param bool   $sub_folder  set false for not adding sub_folders and save all files in main folder
     *
     * @return bool
     * @throws bptException when zip extension not found
     */
    public static function zip (string $path, string $destination, bool $self = true, bool $sub_folder = true): bool {
        if (extension_loaded('zip')) {
            if (file_exists($destination)) unlink($destination);

            $path = realpath($path);
            $zip = new ZipArchive();
            $zip->open($destination, ZipArchive::CREATE);

            if (is_dir($path)){
                if ($self){
                    $dirs = explode('\\',$path);
                    $dir_count = count($dirs);
                    $main_dir = $dirs[$dir_count-1];

                    $path = '';
                    for ($i=0; $i < $dir_count - 1; $i++) {
                        $path .= '\\' . $dirs[$i];
                    }
                    $path = substr($path, 1);
                    $zip->addEmptyDir($main_dir);
                }

                $it = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
                foreach ($files as $file) {
                    if ($file->isFile()){
                        if ($sub_folder){
                            $zip->addFile($file, str_replace($path . '\\', '', $file));
                        }
                        else{
                            $zip->addFile($file, basename($file));
                        }
                    }
                    elseif ($file->isDir() && $sub_folder) {
                        $zip->addEmptyDir(str_replace($path . '\\', '', $file . '\\'));
                    }
                }
            }
            else{
                $zip->addFile($path, basename($path));
            }

            return $zip->close();
        }
        else {
            logger::write("tools::zip function used\nzip extension is not found , It may not be installed or enabled",loggerTypes::ERROR);
            throw new bptException('ZIP_EXTENSION_MISSING');
        }
    }

    /**
     * create normal keyboard and inline keyboard easily
     *
     * you must set keyboard parameter(for normal keyboard) or inline parameter(for inline keyboard)
     *
     * if you set both , keyboard will be processed and inline will be ignored
     *
     *  
     *
     * con for request contact , loc for request location, web||URL for webapp, pull||POLLTYPE for poll
     *
     * e.g. => tools::easyKey([['button 1 in row 1','button 2 in row 1'],['button 1 in row 2'],['contact button in row 3||con'],['location button in row 4||loc']]);
     *
     *  
     *
     * e.g. => tools::easyKey(inline: [[['button 1 in row 1','this is callback button'],['button 2 in row 1','https://this-is-url-button.com']],[['demo button in row 2']]]);
     *
     * @param string[][] $keyboard array of array(as rows) of array(buttons) of string
     * @param array[][]  $inline   array of array(as rows) of array(buttons)
     *
     * @return inlineKeyboardMarkup|replyKeyboardMarkup replyKeyboardMarkup for keyboard and inlineKeyboardMarkup for inline
     * @throws exception
     */
    public static function easyKey(array $keyboard = [], array $inline = []): inlineKeyboardMarkup|replyKeyboardMarkup {
        if (!empty($keyboard)) {
            $keyboard_object = new replyKeyboardMarkup();
            $keyboard_object->setResize_keyboard($keyboard['resize'] ?? true);
            if (isset($keyboard['one_time'])) {
                $keyboard_object->setOne_time_keyboard($keyboard['one_time']) ;
            }
            foreach ($keyboard as $row) {
                $buttons = [];
                foreach ($row as $base_button) {
                    $button_info = explode('||', $base_button);
                    $button = new keyboardButton();
                    $button->setText($button_info[0] ?? $base_button);
                    if (count($button_info) > 1) {
                        if ($button_info[1] === 'con') {
                            $button->setRequest_contact(true);
                        }
                        elseif ($button_info[1] === 'loc') {
                            $button->setRequest_location(true);
                        }
                        elseif ($button_info[1] === 'poll') {
                            $type = $button_info[2] === pollType::QUIZ ? pollType::QUIZ : pollType::REGULAR;
                            $button->setRequest_poll((new keyboardButtonPollType())->setType($type));
                        }
                        elseif ($button_info[1] === 'web' && isset($button_info[2])) {
                            $url = $button_info[2];
                            $button->setWeb_app((new webAppInfo())->setUrl($url));
                        }
                    }
                    $buttons[] = $button;
                }
                $keyboard_object->setKeyboard([$buttons]);
            }
            return $keyboard_object;
        }
        elseif (!empty($inline)) {
            $keyboard_object = new inlineKeyboardMarkup();
            foreach ($inline as $row) {
                $buttons = [];
                foreach ($row as $button_info) {
                    $button = new inlineKeyboardButton();
                    if (isset($button_info[1])) {
                        if (filter_var($button_info[1], FILTER_VALIDATE_URL) && str_starts_with($button_info[1], 'http')) {
                            $button->setText($button_info[0])->setUrl($button_info[1]);
                        }
                        else {
                            $button->setText($button_info[0])->setCallback_data($button_info[1]);
                        }
                    }
                    else {
                        $button->setText($button_info[0])->setUrl('https://t.me/BPT_CH');
                    }
                }
                $keyboard_object->setInline_keyboard([$buttons]);
            }
            return $keyboard_object;
        }
        else {
            logger::write("tools::eKey function used\nkeyboard or inline parameter must be set",loggerTypes::ERROR);
            throw new bptException('ARGUMENT_NOT_FOUND_KEYBOARD_INLINE');
        }
    }
}