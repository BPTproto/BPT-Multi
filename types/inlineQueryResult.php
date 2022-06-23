<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one result of an inline query. Telegram clients currently support results of the
 * following 20 types:
 */
class inlineQueryResult extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
