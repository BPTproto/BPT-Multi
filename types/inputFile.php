<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the contents of a file to be uploaded. Must be posted using multipart/form-data in the
 * usual way that files are uploaded via the browser.
 */
class inputFile extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
