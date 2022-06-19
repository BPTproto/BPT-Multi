<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a Game.
 */
class inlineQueryResultGame extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['reply_markup' => 'BPT\types\inlineKeyboardMarkup'];

    /** Type of the result, must be game */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** Short name of the game */
    public string $game_short_name;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
