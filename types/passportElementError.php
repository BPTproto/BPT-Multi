<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an error in the Telegram Passport element which was submitted that should be resolved
 * by the user. It should be one of:
 */
class passportElementError extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
