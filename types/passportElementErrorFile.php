<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue with a document scan. The error is considered resolved when the file with the document
 * scan changes.
 */
class passportElementErrorFile extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be file */
    public string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of “utility_bill”,
     * “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”
     */
    public string $type;

    /** Base64-encoded file hash */
    public string $file_hash;

    /** Error message */
    public string $message;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
