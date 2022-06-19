<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue with the translated version of a document. The error is considered resolved when a file
 * with the document translation change.
 */
class passportElementErrorTranslationFiles extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be translation_files */
    public string $source;

    /**
     * Type of element of the user's Telegram Passport which has the issue, one of “passport”,
     * “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”,
     * “rental_agreement”, “passport_registration”, “temporary_registration”
     */
    public string $type;

    /** List of base64-encoded file hashes */
    public array $file_hashes;

    /** Error message */
    public string $message;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
