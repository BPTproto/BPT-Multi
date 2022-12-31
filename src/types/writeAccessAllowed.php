<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a user allowing a bot added to the attachment menu to write
 * messages. Currently holds no information.
 */
class writeAccessAllowed extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
