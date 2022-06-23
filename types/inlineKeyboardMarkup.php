<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an inline keyboard that appears right next to the message it belongs to.
 */
class inlineKeyboardMarkup extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['array' => ['inline_keyboard' => 'BPT\types\inlineKeyboardButton']]];

    /**
     * Array of button rows, each represented by an Array of InlineKeyboardButton objects
     * @var inlineKeyboardButton[][]
     */
    public array $inline_keyboard;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
