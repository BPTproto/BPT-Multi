<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the content of a media message to be sent. It should be one of
 */
class inputMedia extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
