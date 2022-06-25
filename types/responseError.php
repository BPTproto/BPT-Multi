<?php

namespace BPT\types;

use stdClass;

/**
 * Response of wrong webhook request which will have the error
 */
class responseError extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** code of error , which will be a number with 3 digit */
    public int $error_code;

    /** human readable error text */
    public string $description;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
