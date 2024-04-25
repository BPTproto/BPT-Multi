<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a custom keyboard with reply options (see Introduction to bots for details and
 * examples).
 * @method self setKeyboard(array $value)
 * @method self setIs_persistent(bool $value)
 * @method self setResize_keyboard(bool $value)
 * @method self setOne_time_keyboard(bool $value)
 * @method self setInput_field_placeholder(string $value)
 * @method self setSelective(bool $value)
 */
class replyKeyboardMarkup extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['array' => ['keyboard' => 'BPT\types\keyboardButton']]];

    /**
     * Array of button rows, each represented by an Array of KeyboardButton objects
     * @var keyboardButton[][]
     */
    public array $keyboard;

    /**
     * Optional. Requests clients to always show the keyboard when the regular keyboard is hidden. Defaults to false,
     * in which case the custom keyboard can be hidden and opened with a keyboard icon.
     */
    public bool $is_persistent;

    /**
     * Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller
     * if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the
     * same height as the app's standard keyboard.
     */
    public bool $resize_keyboard;

    /**
     * Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be
     * available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a
     * special button in the input field to see the custom keyboard again. Defaults to false.
     */
    public bool $one_time_keyboard;

    /** Optional. The placeholder to be shown in the input field when the keyboard is active; 1-64 characters */
    public string $input_field_placeholder;

    /**
     * Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that
     * are mentioned in the text of the Message object; 2) if the bot's message is a reply to a message in the same
     * chat and forum topic, sender of the original message.Example: A user requests to change the bot's language,
     * bot replies to the request with a keyboard to select the new language. Other users in the group don't see the
     * keyboard.
     */
    public bool $selective;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
