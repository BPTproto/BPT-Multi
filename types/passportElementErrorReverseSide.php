<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue with the reverse side of a document. The error is considered resolved when the file with
 * reverse side of the document changes.
 */
class passportElementErrorReverseSide extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be reverse_side */
    public string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of “driver_license”,
     * “identity_card”
     */
    public string $type;

    /** Base64-encoded hash of the file with the reverse side of the document */
    public string $file_hash;

    /** Error message */
    public string $message;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
