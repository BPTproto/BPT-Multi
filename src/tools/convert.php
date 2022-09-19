<?php

namespace BPT\tools;

use BPT\constants\parseMode;
use DateTime;
use Exception;

trait convert {
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
}