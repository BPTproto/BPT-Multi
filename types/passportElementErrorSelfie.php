<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an issue with the selfie with a document. The error is considered resolved when the file with the
 * selfie changes.
 */
class passportElementErrorSelfie extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Error source, must be selfie */
    public string $source;

    /**
     * The section of the user's Telegram Passport which has the issue, one of “passport”, “driver_license”,
     * “identity_card”, “internal_passport”
     */
    public string $type;

    /** Base64-encoded hash of the file with the selfie */
    public string $file_hash;

    /** Error message */
    public string $message;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
