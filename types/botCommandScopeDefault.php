<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the default scope of bot commands. Default commands are used if no commands with a narrower scope
 * are specified for the user.
 */
class botCommandScopeDefault extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Scope type, must be default */
    public string $type;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
