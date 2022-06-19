<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 */
class passportElementErrorUnspecified extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be unspecified */
    public string $source;

    /** Type of element of the user's Telegram Passport which has the issue */
    public string $type;

    /** Base64-encoded element hash */
    public string $element_hash;

    /** Error message */
    public string $message;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
