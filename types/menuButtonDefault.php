<?php

namespace BPT\types;

use stdClass;

/**
 * Describes that no specific value for the menu button was set.
 */
class menuButtonDefault extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Type of the button, must be default */
    public string $type;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
