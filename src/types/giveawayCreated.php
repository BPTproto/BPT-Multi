<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about the creation of a scheduled giveaway. Currently holds no
 * information.
 */
class giveawayCreated extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
