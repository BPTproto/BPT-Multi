<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a unique message identifier.
 */
class messageId extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Unique message identifier */
    public int $message_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
