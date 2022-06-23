<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue with a list of scans. The error is considered resolved when the list of files containing
 * the scans changes.
 */
class passportElementErrorFiles extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be files */
    public string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of “utility_bill”,
     * “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”
     */
    public string $type;

    /**
     * List of base64-encoded file hashes
     * @var string[]
     */
    public array $file_hashes;

    /** Error message */
    public string $message;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
