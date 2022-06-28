<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the content of a message to be sent as a result of an inline query. Telegram clients
 * currently support the following 5 types:
 */
class inputMessageContent extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
