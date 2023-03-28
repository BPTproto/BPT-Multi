<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the bot's description.
 */
class botDescription extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** The bot's description */
    public string $description;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
