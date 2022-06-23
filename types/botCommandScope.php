<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the scope to which bot commands are applied. Currently, the following 7 scopes are
 * supported:
 */
class botCommandScope extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
