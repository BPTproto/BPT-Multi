<?php

namespace BPT;

use BPT\constants\chatMemberStatus;
use BPT\constants\cryptoAction;
use BPT\constants\fields;
use BPT\constants\fileTypes;
use BPT\constants\loggerTypes;
use BPT\constants\parseMode;
use BPT\constants\pollType;
use BPT\exception\bptException;
use BPT\telegram\request;
use BPT\telegram\telegram;
use BPT\types\inlineKeyboardButton;
use BPT\types\inlineKeyboardMarkup;
use BPT\types\keyboardButton;
use BPT\types\keyboardButtonPollType;
use BPT\types\replyKeyboardMarkup;
use BPT\types\user;
use BPT\types\webAppInfo;
use DateTime;
use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * tools class , gather what ever you need
 */
class tools{
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
        $cf_ips = ['173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/12', '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22'];
        foreach ($cf_ips as $cf_ip) {
            if (self::ipInRange($ip,$cf_ip)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check the given IP is from ArvanCloud or not
     *
     * e.g. => tools::isCloudFlare('192.168.1.1');
     *
     * e.g. =>tools::isCloudFlare(ip: '192.168.1.1');
     *
     * @param string $ip Your ip to be checked is ArvanCloud or not
     *
     * @return bool
     */
    public static function isArvanCloud (string $ip): bool {
        $ar_ips = ['185.143.232.0/22', '92.114.16.80/28', '2.146.0.0/28', '46.224.2.32/29', '89.187.178.96/29', '195.181.173.128/29', '89.187.169.88/29', '188.229.116.16/29', '83.123.255.56/31', '164.138.128.28/31', '94.182.182.28/30', '185.17.115.176/30', '5.213.255.36/31', '138.128.139.144/29', '5.200.14.8/29', '188.122.68.224/29', '188.122.83.176/29', '213.179.217.16/29', '185.179.201.192/29', '43.239.139.192/29', '213.179.197.16/29', '213.179.201.192/29', '109.200.214.248/29', '138.128.141.16/29', '188.122.78.136/29', '213.179.211.32/29', '103.194.164.24/29', '185.50.105.136/29', '213.179.213.16/29', '162.244.52.120/29', '188.122.80.240/29', '109.200.195.64/29', '109.200.199.224/29', '185.228.238.0/28', '94.182.153.24/29', '94.101.182.0/27', '37.152.184.208/28', '78.39.156.192/28', '158.255.77.238/31', '81.12.28.16/29', '176.65.192.202/31', '2.144.3.128/28', '89.45.48.64/28', '37.32.16.0/27', '37.32.17.0/27', '37.32.18.0/27'];
        foreach ($ar_ips as $ar_ip) {
            if (self::ipInRange($ip,$ar_ip)) {
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
        if (!preg_match('/^(\d{8,10}):[\w\-]{35}$/', $token)) {
            return false;
        }
        if (!$verify){
            return true;
        }
        $res = telegram::me($token);
        if (!telegram::$status) {
            return false;
        }
        return $res;
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
     * e.g. => tools::isShorted('abc');
     *
     * @param string $text
     *
     * @return bool
     */
    public static function isShorted(string $text): bool{
        return preg_match('/^[a-zA-Z0-9]+$/',$text);
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
        return false;
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
        if (!is_dir($path)) {
            return unlink($path);
        }
        if (count(scandir($path)) <= 2) {
            return rmdir($path);
        }
        if (!$sub) {
            logger::write("tools::delete function used\ndelete function cannot delete folder because its have subFiles and sub parameter haven't true value",loggerTypes::ERROR);
            throw new bptException('DELETE_FOLDER_HAS_SUB');
        }
        $it = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        return rmdir($path);
    }

    /**
     * convert all files in selected path to zip and then save it in dest path
     *
     * e.g. => tools::zip('xFolder','yFolder/xFile.zip');
     *
     * @param string $path        your file or folder to be zipped
     * @param string $destination destination path for create file
     *
     * @return bool
     * @throws bptException when zip extension not found
     */
    public static function zip (string $path, string $destination): bool {
        if (!extension_loaded('zip')) {
            logger::write("tools::zip function used\nzip extension is not found , It may not be installed or enabled", loggerTypes::ERROR);
            throw new bptException('ZIP_EXTENSION_MISSING');
        }
        $rootPath = realpath($path);
        $zip = new ZipArchive();
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if (is_dir($path)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            $root_len = strlen($rootPath) + 1;
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $zip->addFile($filePath, substr($filePath, $root_len));
                }
            }
        }
        else {
            $zip->addFile($path, basename($path));
        }
        return $zip->close();
    }

    /**
     * download url and save it to path
     *
     * e.g. => tools::downloadFile('http://example.com/exmaple.mp4','movie.mp4');
     *
     * @param string $url your url to be downloaded
     * @param string $path destination path for saving url
     * @param int $chunk_size size of each chunk of data (in KB)
     *
     * @return bool true on success and false in failure
     */
    public static function downloadFile (string $url, string $path,int $chunk_size = 512): bool {
        $file = fopen($url, 'rb');
        if (!$file) return false;
        $path = fopen($path, 'wb');
        if (!$path) return false;

        $length = $chunk_size * 1024;
        while (!feof($file)){
            fwrite($path, fread($file, $length), $length);
        }
        fclose($path);
        fclose($file);
        return true;
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
        if (!isset($base_time)) {
            $base_time = '@'.time();
        }
        $base_time = new DateTime($base_time);
        $target_time = new DateTime(is_numeric($target_time) ? '@' . $target_time : $target_time . ' +00:00');

        $diff = $base_time->diff($target_time);

        $string = ['year' => 'y', 'month' => 'm', 'day' => 'd', 'hour' => 'h', 'minute' => 'i', 'second' => 's'];
        foreach ($string as $k => &$v) {
            if ($diff->$v) {
                $v = $diff->$v;
            }
            else unset($string[$k]);
        }
        $string['status'] = $base_time < $target_time ? 'later' : 'ago';

        return count($string) > 1 ? $string : ['status' => 'now'];
    }

    /**
     * same as mysqli::real_escape_string but does not need a db connection and allow array escape
     *
     * e.g. => tools::realEscapeString(input: $text1);
     *
     * e.g. => tools::realEscapeString([$text1,$text2,$text3]);
     *
     * @param string|string[] $input
     *
     * @return string[]|string
     */
    public static function realEscapeString(string|array $input): string|array {
        if(is_array($input)) {
            return array_map(__METHOD__, $input);
        }

        if(!empty($input) && is_string($input)) {
            return str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $input);
        }

        return $input;
    }

    /**
     * replace `search` with `replace` in `subject` but only one of it(the first result)
     *
     * e.g. => tools::strReplaceFirst('hello','bye','hello :)');
     *
     * @param string|string[] $search
     * @param string|string[] $replace
     * @param string|string[] $subject
     *
     * @return string[]|string
     */
    public static function strReplaceFirst(string|array $search, string|array $replace, string|array $subject): string|array {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

    /**
     * Convert file_id to fileType
     *
     * Thank you [Daniil](https://github.com/danog) for file_id decode pattern
     *
     * @param string $file_id
     *
     * @return string see possible values in fileType class
     */
    public static function fileType (string $file_id): string {
        $data = base64_decode(str_pad(strtr($file_id, '-_', '+/'), strlen($file_id) % 4, '='));
        $new = '';
        $last = '';
        foreach (str_split($data) as $char) {
            if ($last === "\0") {
                $new .= str_repeat($last, ord($char));
                $last = '';
            }
            else {
                $new .= $last;
                $last = $char;
            }
        }
        $data = unpack('VtypeId/Vdc_id', $new . $last);
        $data['typeId'] = $data['typeId'] & ~33554432 & ~16777216;
        return [
            fileTypes::THUMBNAIL,
            fileTypes::PROFILE_PHOTO,
            fileTypes::PHOTO,
            fileTypes::VOICE,
            fileTypes::VIDEO,
            fileTypes::DOCUMENT,
            fileTypes::ENCRYPTED,
            fileTypes::TEMP,
            fileTypes::STICKER,
            fileTypes::AUDIO,
            fileTypes::ANIMATION,
            fileTypes::ENCRYPTED_THUMBNAIL,
            fileTypes::WALLPAPER,
            fileTypes::VIDEO_NOTE,
            fileTypes::SECURE_RAW,
            fileTypes::SECURE,
            fileTypes::BACKGROUND,
            fileTypes::SIZE
        ][$data['typeId']];
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
     * @param string[][] $keyboard array(as rows) of array(buttons) of string
     * @param array[][]  $inline   array(as rows) of array(buttons) of array(button data)
     *
     * @return inlineKeyboardMarkup|replyKeyboardMarkup replyKeyboardMarkup for keyboard and inlineKeyboardMarkup for inline
     * @throws bptException
     */
    public static function easyKey(array $keyboard = [], array $inline = []): inlineKeyboardMarkup|replyKeyboardMarkup {
        if (!empty($keyboard)) {
            $keyboard_object = new replyKeyboardMarkup();
            $keyboard_object->setResize_keyboard($keyboard['resize'] ?? true);
            if (isset($keyboard['one_time'])) {
                $keyboard_object->setOne_time_keyboard($keyboard['one_time']);
            }
            $rows = [];
            foreach ($keyboard as $row) {
                if (!is_array($row)) continue;
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
                $rows[] = $buttons;
            }
            $keyboard_object->setKeyboard($rows);
            return $keyboard_object;
        }
        elseif (!empty($inline)) {
            $keyboard_object = new inlineKeyboardMarkup();
            $rows = [];
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
                    $buttons[] = $button;
                }
                $rows[] = $buttons;
            }
            $keyboard_object->setInline_keyboard($rows);
            return $keyboard_object;
        }
        else {
            logger::write("tools::eKey function used\nkeyboard or inline parameter must be set",loggerTypes::ERROR);
            throw new bptException('ARGUMENT_NOT_FOUND_KEYBOARD_INLINE');
        }
    }

    /**
     * create invite link for user which use shortEncode method and can be handled by BPT database
     *
     * e.g. => tools::inviteLink(123456789,'Username_bot');
     *
     * e.g. => tools::inviteLink(123456789);
     *
     * @param int|null $user_id user id , default : catchFields(fields::USER_ID)
     * @param string|null  $bot_username bot username , default : telegram::getMe()->username
     *
     * @return string
     */
    public static function inviteLink (int $user_id = null, string $bot_username = null): string {
        if (empty($user_id)) $user_id = telegram::catchFields(fields::USER_ID);
        if (empty($bot_username)) $bot_username = telegram::getMe()->username;
        return 'https://t.me/' . str_replace('@', '', $bot_username) . '?start=ref_' . tools::shortEncode($user_id);
    }

    /**
     * encrypt or decrypt a text with really high security
     *
     * action parameter must be `encrypt` or `decrypt` ( use cryptoAction constant class for easy use )
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
        if (!extension_loaded('openssl')) {
            logger::write("tools::crypto function used\nopenssl extension is not found , It may not be installed or enabled",loggerTypes::ERROR);
            throw new bptException('OPENSSL_EXTENSION_MISSING');
        }
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
            logger::write("tools::crypto function used\naction is not right, its must be `encode` or `decode`",loggerTypes::WARNING);
            return false;
        }
    }

    /**
     * encode int to a string
     *
     * e.g. => tools::shortEncode(123456789);
     *
     * @param int $num
     *
     * @return string
     */
    public static function shortEncode(int $num): string {
        $codes = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $array = [];
        while ($num > 0){
            $array[] = $num % 62;
            $num = floor($num / 62);
        }
        if (count($array) < 1) $array = [0];
        foreach ($array as &$value) {
            $value = $codes[$value];
        }
        return strrev(implode('',$array));
    }

    /**
     * decode string to int
     *
     * e.g. => tools::shortDecode('8m0Kx');
     *
     * @param string $text
     *
     * @return int
     */
    public static function shortDecode(string $text): int{
        $codes = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = 0;
        $text = str_split(strrev($text));
        foreach ($text as $key=>$value) {
            $num += strpos($codes,$value) * pow(62,$key);
        }
        return $num;
    }
}