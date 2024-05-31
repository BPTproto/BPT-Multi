<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one button of an inline keyboard. Exactly one of the optional fields must be used to
 * specify type of the button.
 * @method self setText(string $value)
 * @method self setUrl(string $value)
 * @method self setCallback_data(string $value)
 * @method self setWeb_app(webAppInfo $value)
 * @method self setLogin_url(loginUrl $value)
 * @method self setSwitch_inline_query(string $value)
 * @method self setSwitch_inline_query_current_chat(string $value)
 * @method self setSwitch_inline_query_chosen_chat(switchInlineQueryChosenChat $value)
 * @method self setCallback_game(callbackGame $value)
 * @method self setPay(bool $value)
 */
class inlineKeyboardButton extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'web_app' => 'BPT\types\webAppInfo',
        'login_url' => 'BPT\types\loginUrl',
        'switch_inline_query_chosen_chat' => 'BPT\types\switchInlineQueryChosenChat',
        'callback_game' => 'BPT\types\callbackGame',
    ];

    /** Label text on the button */
    public string $text;

    /**
     * Optional. HTTP or tg:// URL to be opened when the button is pressed. Links tg://user?id=<user_id> can be used
     * to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
     */
    public string $url;

    /**
     * Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes. Not supported for
     * messages sent on behalf of a Telegram Business account.
     */
    public string $callback_data;

    /**
     * Optional. Description of the Web App that will be launched when the user presses the button. The Web App will
     * be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Available only
     * in private chats between a user and the bot. Not supported for messages sent on behalf of a Telegram Business
     * account.
     */
    public webAppInfo $web_app;

    /**
     * Optional. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the Telegram
     * Login Widget.
     */
    public loginUrl $login_url;

    /**
     * Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and
     * insert the bot's username and the specified inline query in the input field. May be empty, in which case just
     * the bot's username will be inserted. Not supported for messages sent on behalf of a Telegram Business account.
     */
    public string $switch_inline_query;

    /**
     * Optional. If set, pressing the button will insert the bot's username and the specified inline query in the
     * current chat's input field. May be empty, in which case only the bot's username will be inserted.This offers a
     * quick way for the user to open your bot in inline mode in the same chat - good for selecting something from
     * multiple options. Not supported in channels and for messages sent on behalf of a Telegram Business account.
     */
    public string $switch_inline_query_current_chat;

    /**
     * Optional. If set, pressing the button will prompt the user to select one of their chats of the specified type,
     * open that chat and insert the bot's username and the specified inline query in the input field. Not supported
     * for messages sent on behalf of a Telegram Business account.
     */
    public switchInlineQueryChosenChat $switch_inline_query_chosen_chat;

    /**
     * Optional. Description of the game that will be launched when the user presses the button.NOTE: This type of
     * button must always be the first button in the first row.
     */
    public callbackGame $callback_game;

    /**
     * Optional. Specify True, to send a Pay button. Substrings “⭐” and “XTR” in the buttons's text will be
     * replaced with a Telegram Star icon.NOTE: This type of button must always be the first button in the first row
     * and can only be used in invoice messages.
     */
    public bool $pay;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
