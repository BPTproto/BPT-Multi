<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object represents an incoming callback query from a callback button in an inline keyboard. If the button
 * that originated the query was attached to a message sent by the bot, the field message will be present. If the
 * button was attached to a message sent via the bot (in inline mode), the field inline_message_id will be
 * present. Exactly one of the fields data or game_short_name will be present.
 */
class callbackQuery extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['from' => 'BPT\types\user', 'message' => 'BPT\types\message'];

    /** Unique identifier for this query */
    public string $id;

    /** Sender */
    public user $from;

    /**
     * Optional. Message with the callback button that originated the query. Note that message content and message
     * date will not be available if the message is too old
     */
    public null|maybeInaccessibleMessage $message = null;

    /** Optional. Identifier of the message sent via the bot in inline mode, that originated the query. */
    public null|string $inline_message_id = null;

    /**
     * Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent.
     * Useful for high scores in games.
     */
    public null|string $chat_instance = null;

    /**
     * Optional. Data associated with the callback button. Be aware that the message originated the query can contain
     * no callback buttons with this data.
     */
    public null|string $data = null;

    /** Optional. Short name of a Game to be returned, serves as the unique identifier for the game */
    public null|string $game_short_name = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Use this method to send answers to callback queries sent from inline keyboards.
     * The answer will be displayed to the user as a notification at the top of the chat screen or as an alert.
     * On success, True is returned.
     *
     * @param null|string $text
     * @param null|bool   $show_alert
     * @param null|string $url
     * @param null|int    $cache_time
     * @param bool|null   $answer
     *
     * @return responseError|bool
     */
    public function answer (string|null $text = null, bool|null $show_alert = null, string|null $url = null, int|null $cache_time = null, bool $answer = null): responseError|bool {
        return telegram::answerCallbackQuery($this->id, $text, $show_alert, $url, $cache_time, answer: $answer);
    }

    /**
     * Edit text of the message
     *
     * @param string    $text
     * @param bool|null $answer
     *
     * @return message|responseError|bool
     */
    public function editText (string $text, bool $answer = null): message|responseError|bool {
        return telegram::editMessageText($text, answer: $answer);
    }

    /**
     * Edit caption of the message media
     *
     * @param string    $text
     * @param bool|null $answer
     *
     * @return message|responseError|bool
     */
    public function editCaption (string $text = '', bool $answer = null): message|responseError|bool {
        return telegram::editMessageCaption(caption: $text, answer: $answer);
    }

    /**
     * Edit keyboard of the message
     *
     * @param inlineKeyboardMarkup|stdClass|array|null $reply_markup
     * @param bool|null                                $answer
     *
     * @return message|responseError|bool
     */
    public function editKeyboard (inlineKeyboardMarkup|stdClass|array $reply_markup = null, bool $answer = null): message|responseError|bool {
        return telegram::editMessageReplyMarkup(reply_markup: $reply_markup, answer: $answer);
    }

    /**
     * Edit media of the message
     *
     * @param inputMedia|array|stdClass $media
     * @param bool|null                 $answer
     *
     * @return message|responseError|bool
     */
    public function editMedia (inputMedia|array|stdClass $media, bool $answer = null): message|responseError|bool {
        return telegram::editMessageMedia($media, answer: $answer);
    }
}
