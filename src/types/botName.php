<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the bot's name.
 */
class botName extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** The bot's name */
    public string $name;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
