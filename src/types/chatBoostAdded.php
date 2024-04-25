<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a user boosting a chat.
 */
class chatBoostAdded extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Number of boosts added by the user */
    public int $boost_count;

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
