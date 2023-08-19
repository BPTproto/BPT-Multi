<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a message about a forwarded story in the chat. Currently holds no information.
 */
class story extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
