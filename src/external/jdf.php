<?php

namespace BPT\external;

/**
 * Time and date class for persian calendar(Solar calendar, Shamsi calendar)
 *
 * @Author : Reza Gholampanahi & WebSite : http://jdf.scr.ir
 * @License: GNU/LGPL _ Open Source & Free : [all functions]
 * @Version: 2.76 =>[ 1399/11/28 = 1442/07/04 = 2021/02/16 ]
 */
class jdf {
    public static function jdate ($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa') {
        $T_sec = 0;
        if ($time_zone != 'local') {
            date_default_timezone_set(empty($time_zone) ? 'Asia/Tehran' : $time_zone);
        }
        $timestamp = $T_sec + empty($timestamp) ? time() : self::tr_num($timestamp);
        $date = explode('_', date('H_i_j_n_O_P_s_w_Y', $timestamp));
        [$jalali_year, $jalali_month, $jalali_day] = self::gregorian_to_jalali($date[8], $date[3], $date[2]);
        $doy = $jalali_month < 7 ? ($jalali_month - 1) * 31 + $jalali_day - 1 : ($jalali_month - 7) * 30 + $jalali_day + 185;
        $leap_year = ($jalali_year + 12) % 33 % 4 == 1 ? 1 : 0;
        $length = strlen($format);
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub == '\\') {
                $output .= substr($format, ++$i, 1);
                continue;
            }
            switch ($sub) {
                case 'E':
                case 'R':
                case 'x':
                case 'X':
                    $output .= 'http://jdf.scr.ir';
                    break;
                case 'B':
                case 'e':
                case 'g':
                case 'G':
                case 'h':
                case 'I':
                case 'T':
                case 'u':
                case 'Z':
                    $output .= date($sub, $timestamp);
                    break;
                case 'a':
                    $output .= $date[0] < 12 ? 'ق.ظ' : 'ب.ظ';
                    break;
                case 'A':
                    $output .= $date[0] < 12 ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;
                case 'b':
                    $output .= (int) ($jalali_month / 3.1) + 1;
                    break;
                case 'c':
                    $output .= $jalali_year . '/' . $jalali_month . '/' . $jalali_day . ' ،' . $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[5];
                    break;
                case 'C':
                    $output .= (int) (($jalali_year + 99) / 100);
                    break;
                case 'd':
                    $output .= $jalali_day < 10 ? '0' . $jalali_day : $jalali_day;
                    break;
                case 'D':
                    $output .= self::jdate_words(['kh' => $date[7]], ' ');
                    break;
                case 'f':
                    $output .= self::jdate_words(['ff' => $jalali_month], ' ');
                    break;
                case 'F':
                    $output .= self::jdate_words(['mm' => $jalali_month], ' ');
                    break;
                case 'H':
                    $output .= $date[0];
                    break;
                case 'i':
                    $output .= $date[1];
                    break;
                case 'j':
                    $output .= $jalali_day;
                    break;
                case 'J':
                    $output .= self::jdate_words(['rr' => $jalali_day], ' ');
                    break;
                case 'k';
                    $output .= self::tr_num(100 - (int) ($doy / ($leap_year + 365.24) * 1000) / 10, $tr_num);
                    break;
                case 'K':
                    $output .= self::tr_num((int) ($doy / ($leap_year + 365.24) * 1000) / 10, $tr_num);
                    break;
                case 'l':
                    $output .= self::jdate_words(['rh' => $date[7]], ' ');
                    break;
                case 'L':
                    $output .= $leap_year;
                    break;
                case 'm':
                    $output .= $jalali_month > 9 ? $jalali_month : '0' . $jalali_month;
                    break;
                case 'M':
                    $output .= self::jdate_words(['km' => $jalali_month], ' ');
                    break;
                case 'n':
                    $output .= $jalali_month;
                    break;
                case 'N':
                    $output .= $date[7] + 1;
                    break;
                case 'o':
                    $jdw = $date[7] == 6 ? 0 : $date[7] + 1;
                    $dny = 364 + $leap_year - $doy;
                    $output .= ($jdw > $doy + 3 and $doy < 3) ? $jalali_year - 1 : ((3 - $dny > $jdw and $dny < 3) ? $jalali_year + 1 : $jalali_year);
                    break;
                case 'O':
                    $output .= $date[4];
                    break;
                case 'p':
                    $output .= self::jdate_words(['mb' => $jalali_month], ' ');
                    break;
                case 'P':
                    $output .= $date[5];
                    break;
                case 'q':
                    $output .= self::jdate_words(['sh' => $jalali_year], ' ');
                    break;
                case 'Q':
                    $output .= $leap_year + 364 - $doy;
                    break;
                case 'r':
                    $key = self::jdate_words(['rh' => $date[7], 'mm' => $jalali_month]);
                    $output .= $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[4] . ' ' . $key['rh'] . '، ' . $jalali_day . ' ' . $key['mm'] . ' ' . $jalali_year;
                    break;
                case 's':
                    $output .= $date[6];
                    break;
                case 'S':
                    $output .= 'ام';
                    break;
                case 't':
                    $output .= $jalali_month != 12 ? 31 - (int) ($jalali_month / 6.5) : ($leap_year + 29);
                    break;
                case 'U':
                    $output .= $timestamp;
                    break;
                case 'v':
                    $output .= self::jdate_words(['ss' => ($jalali_year % 100)], ' ');
                    break;
                case 'V':
                    $output .= self::jdate_words(['ss' => $jalali_year], ' ');
                    break;
                case 'w':
                    $output .= $date[7] == 6 ? 0 : $date[7] + 1;
                    break;
                case 'W':
                    $avs = ($date[7] == 6 ? 0 : $date[7] + 1) - $doy % 7;
                    if ($avs < 0) $avs += 7;
                    $num = (int) (($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    }
                    elseif ($num < 1) {
                        $num = ($avs == 4 or $avs == ($jalali_year % 33 % 4 - 2 == (int) ($jalali_year % 33 * 0.05) ? 5 : 4)) ? 53 : 52;
                    }
                    $aks = $avs + $leap_year;
                    if ($aks == 7) {
                        $aks = 0;
                    }
                    $output .= ($leap_year + 363 - $doy < $aks and $aks < 3) ? '01' : ($num < 10 ? '0' . $num : $num);
                    break;
                case 'y':
                    $output .= substr($jalali_year, 2, 2);
                    break;
                case 'Y':
                    $output .= $jalali_year;
                    break;
                case 'z':
                    $output .= $doy;
                    break;
                default:
                    $output .= $sub;
            }
        }
        return $tr_num != 'en' ? self::tr_num($output, 'fa', '.') : $output;
    }
    public static function jstrftime ($format, $timestamp = '', $none = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa') {
        $T_sec = 0;/* <= رفع خطاي زمان سرور ، با اعداد '+' و '-' بر حسب ثانيه */
        if ($time_zone != 'local') date_default_timezone_set(($time_zone === '') ? 'Asia/Tehran' : $time_zone);
        $timestamp = $T_sec + (($timestamp === '') ? time() : self::tr_num($timestamp));
        $date = explode('_', date('h_H_i_j_n_s_w_Y', $timestamp));
        [$jalali_year, $jalali_month, $jalali_day] = self::gregorian_to_jalali($date[7], $date[4], $date[3]);
        $doy = ($jalali_month < 7) ? (($jalali_month - 1) * 31) + $jalali_day - 1 : (($jalali_month - 7) * 30) + $jalali_day + 185;
        $leap_year = (((($jalali_year + 12) % 33) % 4) == 1) ? 1 : 0;
        $length = strlen($format);
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $sub = substr($format, $i, 1);
            if ($sub == '%') {
                $sub = substr($format, ++$i, 1);
            }
            else {
                $output .= $sub;
                continue;
            }
            switch ($sub) {

                /* Day */ case 'a':
                $output .= self::jdate_words(['kh' => $date[6]], ' ');
                break;
                case 'A':
                    $output .= self::jdate_words(['rh' => $date[6]], ' ');
                    break;
                case 'd':
                    $output .= ($jalali_day < 10) ? '0' . $jalali_day : $jalali_day;
                    break;
                case 'e':
                    $output .= ($jalali_day < 10) ? ' ' . $jalali_day : $jalali_day;
                    break;
                case 'j':
                    $output .= str_pad($doy + 1, 3, 0, STR_PAD_LEFT);
                    break;
                case 'u':
                    $output .= $date[6] + 1;
                    break;
                case 'w':
                    $output .= ($date[6] == 6) ? 0 : $date[6] + 1;
                    break;
                /* Week */ case 'U':
                $avs = (($date[6] < 5) ? $date[6] + 2 : $date[6] - 5) - ($doy % 7);
                if ($avs < 0) $avs += 7;
                $num = (int) (($doy + $avs) / 7) + 1;
                if ($avs > 3 or $avs == 1) $num--;
                $output .= ($num < 10) ? '0' . $num : $num;
                break;
                case 'V':
                    $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                    if ($avs < 0) $avs += 7;
                    $num = (int) (($doy + $avs) / 7);
                    if ($avs < 4) {
                        $num++;
                    }
                    elseif ($num < 1) {
                        $num = ($avs == 4 or $avs == ((((($jalali_year % 33) % 4) - 2) == ((int) (($jalali_year % 33) * 0.05))) ? 5 : 4)) ? 53 : 52;
                    }
                    $aks = $avs + $leap_year;
                    if ($aks == 7) $aks = 0;
                    $output .= (($leap_year + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
                    break;
                case 'W':
                    $avs = (($date[6] == 6) ? 0 : $date[6] + 1) - ($doy % 7);
                    if ($avs < 0) $avs += 7;
                    $num = (int) (($doy + $avs) / 7) + 1;
                    if ($avs > 3) $num--;
                    $output .= ($num < 10) ? '0' . $num : $num;
                    break;
                /* Month */ case 'b':
                case 'h':
                    $output .= self::jdate_words(['km' => $jalali_month], ' ');
                    break;
                case 'B':
                    $output .= self::jdate_words(['mm' => $jalali_month], ' ');
                    break;
                case 'm':
                    $output .= ($jalali_month > 9) ? $jalali_month : '0' . $jalali_month;
                    break;
                /* Year */ case 'C':
                $tmp = (int) ($jalali_year / 100);
                $output .= ($tmp > 9) ? $tmp : '0' . $tmp;
                break;
                case 'g':
                    $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                    $dny = 364 + $leap_year - $doy;
                    $output .= substr(($jdw > ($doy + 3) and $doy < 3) ? $jalali_year - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $jalali_year + 1 : $jalali_year), 2, 2);
                    break;
                case 'G':
                    $jdw = ($date[6] == 6) ? 0 : $date[6] + 1;
                    $dny = 364 + $leap_year - $doy;
                    $output .= ($jdw > ($doy + 3) and $doy < 3) ? $jalali_year - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $jalali_year + 1 : $jalali_year);
                    break;
                case 'y':
                    $output .= substr($jalali_year, 2, 2);
                    break;
                case 'Y':
                    $output .= $jalali_year;
                    break;
                /* Time */ case 'H':
                $output .= $date[1];
                break;
                case 'I':
                    $output .= $date[0];
                    break;
                case 'l':
                    $output .= ($date[0] > 9) ? $date[0] : ' ' . (int) $date[0];
                    break;
                case 'M':
                    $output .= $date[2];
                    break;
                case 'p':
                    $output .= ($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;
                case 'P':
                    $output .= ($date[1] < 12) ? 'ق.ظ' : 'ب.ظ';
                    break;
                case 'r':
                    $output .= $date[0] . ':' . $date[2] . ':' . $date[5] . ' ' . (($date[1] < 12) ? 'قبل از ظهر' : 'بعد از ظهر');
                    break;
                case 'R':
                    $output .= $date[1] . ':' . $date[2];
                    break;
                case 'S':
                    $output .= $date[5];
                    break;
                case 'T':
                    $output .= $date[1] . ':' . $date[2] . ':' . $date[5];
                    break;
                case 'X':
                    $output .= $date[0] . ':' . $date[2] . ':' . $date[5];
                    break;
                case 'z':
                    $output .= date('O', $timestamp);
                    break;
                case 'Z':
                    $output .= date('T', $timestamp);
                    break;
                /* Time and Date Stamps */ case 'c':
                $key = self::jdate_words(['rh' => $date[6], 'mm' => $jalali_month]);
                $output .= $date[1] . ':' . $date[2] . ':' . $date[5] . ' ' . date('P', $timestamp) . ' ' . $key['rh'] . '، ' . $jalali_day . ' ' . $key['mm'] . ' ' . $jalali_year;
                break;
                case 'D':
                    $output .= substr($jalali_year, 2, 2) . '/' . (($jalali_month > 9) ? $jalali_month : '0' . $jalali_month) . '/' . (($jalali_day < 10) ? '0' . $jalali_day : $jalali_day);
                    break;
                case 'F':
                    $output .= $jalali_year . '-' . (($jalali_month > 9) ? $jalali_month : '0' . $jalali_month) . '-' . (($jalali_day < 10) ? '0' . $jalali_day : $jalali_day);
                    break;
                case 's':
                    $output .= $timestamp;
                    break;
                case 'x':
                    $output .= substr($jalali_year, 2, 2) . '/' . (($jalali_month > 9) ? $jalali_month : '0' . $jalali_month) . '/' . (($jalali_day < 10) ? '0' . $jalali_day : $jalali_day);
                    break;
                /* Miscellaneous */ case 'n':
                $output .= "\n";
                break;
                case 't':
                    $output .= "\t";
                    break;
                case '%':
                    $output .= '%';
                    break;
                default:
                    $output .= $sub;
            }
        }
        return ($tr_num != 'en') ? self::tr_num($output, 'fa', '.') : $output;
    }
    public static function jmktime ($hour = '', $minute = '', $second = '', $jalali_month = '', $jalali_day = '', $jalali_year = '', $none = '', $timezone = 'Asia/Tehran'): bool|int {
        if ($timezone != 'local') date_default_timezone_set($timezone);
        if ($hour === '') {
            return time();
        }
        else {
            [
                $hour,
                $minute,
                $second,
                $jalali_month,
                $jalali_day,
                $jalali_year
            ] = explode('_', self::tr_num($hour . '_' . $minute . '_' . $second . '_' . $jalali_month . '_' . $jalali_day . '_' . $jalali_year));
            if ($minute === '') {
                return mktime($hour);
            }
            else {
                if ($second === '') {
                    return mktime($hour, $minute);
                }
                else {
                    if ($jalali_month === '') {
                        return mktime($hour, $minute, $second);
                    }
                    else {
                        $jdate = explode('_', self::jdate('Y_j', '', '', $timezone, 'en'));
                        if ($jalali_day === '') {
                            [
                                $gregorian_year,
                                $gregorian_month,
                                $gregorian_day
                            ] = self::jalali_to_gregorian($jdate[0], $jalali_month, $jdate[1]);
                            return mktime($hour, $minute, $second, $gregorian_month);
                        }
                        else {
                            if ($jalali_year === '') {
                                [
                                    $gregorian_year,
                                    $gregorian_month,
                                    $gregorian_day
                                ] = self::jalali_to_gregorian($jdate[0], $jalali_month, $jalali_day);
                                return mktime($hour, $minute, $second, $gregorian_month, $gregorian_day);
                            }
                            else {
                                [
                                    $gregorian_year,
                                    $gregorian_month,
                                    $gregorian_day
                                ] = self::jalali_to_gregorian($jalali_year, $jalali_month, $jalali_day);
                                return mktime($hour, $minute, $second, $gregorian_month, $gregorian_day, $gregorian_year);
                            }
                        }
                    }
                }
            }
        }
    }
    public static function jgetdate ($timestamp = '', $none = '', $timezone = 'Asia/Tehran', $tn = 'en') {
        $timestamp = ($timestamp === '') ? time() : self::tr_num($timestamp);
        $jdate = explode('_', self::jdate('F_G_i_j_l_n_s_w_Y_z', $timestamp, '', $timezone, $tn));
        return [
            'seconds' => self::tr_num((int) self::tr_num($jdate[6]), $tn),
            'minutes' => self::tr_num((int) self::tr_num($jdate[2]), $tn),
            'hours'   => $jdate[1],
            'mday'    => $jdate[3],
            'wday'    => $jdate[7],
            'mon'     => $jdate[5],
            'year'    => $jdate[8],
            'yday'    => $jdate[9],
            'weekday' => $jdate[4],
            'month'   => $jdate[0],
            0         => self::tr_num($timestamp, $tn)
        ];
    }
    public static function jcheckdate ($jalali_month, $jalali_day, $jalali_year): bool {
        [$jalali_month, $jalali_day, $jalali_year] = explode('_', self::tr_num($jalali_month . '_' . $jalali_day . '_' . $jalali_year));
        $l_d = ($jalali_month == 12 and ($jalali_year + 12) % 33 % 4 != 1) ? 29 : 31 - (int) ($jalali_month / 6.5);
        return !(($jalali_month > 12 or $jalali_day > $l_d or $jalali_month < 1 or $jalali_day < 1 or $jalali_year < 1));
    }
    public static function tr_num ($string, $mod = 'en', $mf = '٫'): array|string {
        $english_number = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $persian_number = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf];
        return $mod == 'fa' ? str_replace($english_number, $persian_number, $string) : str_replace($persian_number, $english_number, $string);
    }
    public static function jdate_words ($array, $splitter = '') {
        foreach ($array as $type => $num) {
            $num = (int) self::tr_num($num);
            switch ($type) {
                case 'ss':
                    $length = strlen($num);
                    $xy3 = substr($num, 2 - $length, 1);
                    $h3 = $h34 = $h4 = '';
                    if ($xy3 == 1) {
                        $p34 = '';
                        $k34 = [
                            'ده',
                            'یازده',
                            'دوازده',
                            'سیزده',
                            'چهارده',
                            'پانزده',
                            'شانزده',
                            'هفده',
                            'هجده',
                            'نوزده'
                        ];
                        $h34 = $k34[substr($num, 2 - $length, 2) - 10];
                    }
                    else {
                        $xy4 = substr($num, 3 - $length, 1);
                        $p34 = ($xy3 == 0 or $xy4 == 0) ? '' : ' و ';
                        $k3 = ['', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
                        $h3 = $k3[$xy3];
                        $k4 = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
                        $h4 = $k4[$xy4];
                    }
                    $array[$type] = (($num > 99) ? str_replace(['12', '13', '14', '19', '20'], [
                                'هزار و دویست',
                                'هزار و سیصد',
                                'هزار و چهارصد',
                                'هزار و نهصد',
                                'دوهزار'
                            ], substr($num, 0, 2)) . (substr($num, 2, 2) == '00' ? '' : ' و ') : '') . $h3 . $p34 . $h34 . $h4;
                    break;
                case 'mm':
                    $array[$type] = [
                        'فروردین',
                        'اردیبهشت',
                        'خرداد',
                        'تیر',
                        'مرداد',
                        'شهریور',
                        'مهر',
                        'آبان',
                        'آذر',
                        'دی',
                        'بهمن',
                        'اسفند'
                    ][$num - 1];
                    break;
                case 'rr':
                    $array[$type] = [
                        'یک',
                        'دو',
                        'سه',
                        'چهار',
                        'پنج',
                        'شش',
                        'هفت',
                        'هشت',
                        'نه',
                        'ده',
                        'یازده',
                        'دوازده',
                        'سیزده',
                        'چهارده',
                        'پانزده',
                        'شانزده',
                        'هفده',
                        'هجده',
                        'نوزده',
                        'بیست',
                        'بیست و یک',
                        'بیست و دو',
                        'بیست و سه',
                        'بیست و چهار',
                        'بیست و پنج',
                        'بیست و شش',
                        'بیست و هفت',
                        'بیست و هشت',
                        'بیست و نه',
                        'سی',
                        'سی و یک'
                    ][$num - 1];
                    break;
                case 'rh':
                    $array[$type] = ['یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'][$num];
                    break;
                case 'sh':
                    $array[$type] = [
                        'مار',
                        'اسب',
                        'گوسفند',
                        'میمون',
                        'مرغ',
                        'سگ',
                        'خوک',
                        'موش',
                        'گاو',
                        'پلنگ',
                        'خرگوش',
                        'نهنگ'
                    ][$num % 12];
                    break;
                case 'mb':
                    $array[$type] = [
                        'حمل',
                        'ثور',
                        'جوزا',
                        'سرطان',
                        'اسد',
                        'سنبله',
                        'میزان',
                        'عقرب',
                        'قوس',
                        'جدی',
                        'دلو',
                        'حوت'
                    ][$num - 1];
                    break;
                case 'ff':
                    $array[$type] = ['بهار', 'تابستان', 'پاییز', 'زمستان'][(int) ($num / 3.1)];
                    break;
                case 'km':
                    $array[$type] = [
                        'فر',
                        'ار',
                        'خر',
                        'تی‍',
                        'مر',
                        'شه‍',
                        'مه‍',
                        'آب‍',
                        'آذ',
                        'دی',
                        'به‍',
                        'اس‍'
                    ][$num - 1];
                    break;
                case 'kh':
                    $array[$type] = ['ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش'][$num];
                    break;
                default:
                    $array[$type] = $num;
            }
        }
        return $splitter === '' ? $array : implode($splitter, $array);
    }
    public static function gregorian_to_jalali ($gregorian_year, $gregorian_month, $gregorian_day, $splitter = ''): array|string {
        [$gregorian_year, $gregorian_month, $gregorian_day] = explode('_', self::tr_num($gregorian_year . '_' . $gregorian_month . '_' . $gregorian_day));/* <= Extra :اين سطر ، جزء تابع اصلي نيست */
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gregorian_year2 = ($gregorian_month > 2) ? ($gregorian_year + 1) : $gregorian_year;
        $days = 355666 + (365 * $gregorian_year) + ((int) (($gregorian_year2 + 3) / 4)) - ((int) (($gregorian_year2 + 99) / 100)) + ((int) (($gregorian_year2 + 399) / 400)) + $gregorian_day + $g_d_m[$gregorian_month - 1];
        $jalali_year = -1595 + (33 * ((int) ($days / 12053)));
        $days %= 12053;
        $jalali_year += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $jalali_year += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jalali_month = 1 + (int) ($days / 31);
            $jalali_day = 1 + ($days % 31);
        }
        else {
            $jalali_month = 7 + (int) (($days - 186) / 30);
            $jalali_day = 1 + (($days - 186) % 30);
        }
        return $splitter == '' ? [$jalali_year, $jalali_month, $jalali_day] : $jalali_year . $splitter . $jalali_month . $splitter . $jalali_day;
    }
    public static function jalali_to_gregorian ($jalali_year, $jalali_month, $jalali_day, $splitter = ''): array|string {
        [$jalali_year, $jalali_month, $jalali_day] = explode('_', self::tr_num($jalali_year . '_' . $jalali_month . '_' . $jalali_day));
        $jalali_year += 1595;
        $days = -355668 + (365 * $jalali_year) + (((int) ($jalali_year / 33)) * 8) + ((int) ((($jalali_year % 33) + 3) / 4)) + $jalali_day + (($jalali_month < 7) ? ($jalali_month - 1) * 31 : (($jalali_month - 7) * 30) + 186);
        $gregorian_year = 400 * (int) ($days / 146097);
        $days %= 146097;
        if ($days > 36524) {
            $gregorian_year += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }
        $gregorian_year += 4 * ((int) ($days / 1461));
        $days %= 1461;
        if ($days > 365) {
            $gregorian_year += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        $gregorian_day = $days + 1;
        $month_days = [
            0,
            31,
            (($gregorian_year % 4 == 0 and $gregorian_year % 100 != 0) or ($gregorian_year % 400 == 0)) ? 29 : 28,
            31,
            30,
            31,
            30,
            31,
            31,
            30,
            31,
            30,
            31
        ];
        for ($gregorian_month = 0; $gregorian_month < 13 and $gregorian_day > $month_days[$gregorian_month]; $gregorian_month++) {
            $gregorian_day -= $month_days[$gregorian_month];
        }
        return $splitter == '' ? [$gregorian_year, $gregorian_month, $gregorian_day] : $gregorian_year . $splitter . $gregorian_month . $splitter . $gregorian_day;
    }
}