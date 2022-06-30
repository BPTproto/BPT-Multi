<?php

namespace BPT\tools;

use BPT\constants\loggerTypes;
use BPT\constants\pollType;
use BPT\exception\bptException;
use BPT\logger;
use BPT\types\inlineKeyboardButton;
use BPT\types\inlineKeyboardMarkup;
use BPT\types\keyboardButton;
use BPT\types\keyboardButtonPollType;
use BPT\types\replyKeyboardMarkup;
use BPT\types\webAppInfo;

trait generator {
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