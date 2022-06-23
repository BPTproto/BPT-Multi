<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about one member of a chat. Currently, the following 6 types of chat members
 * are supported:
 */
class chatMember extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
