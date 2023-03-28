<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the bot's short description.
 */
class botShortDescription extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** The bot's short description */
    public string $short_description;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
