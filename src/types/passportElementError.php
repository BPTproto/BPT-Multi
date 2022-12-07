<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an error in the Telegram Passport element which was submitted that should be resolved
 * by the user.
 * @method self setSource(string $value)
 * @method self setType(string $value)
 * @method self setField_name(string $value)
 * @method self setData_hash(string $value)
 * @method self setMessage(string $value)
 * @method self setFile_hash(string $value)
 * @method self setFile_hashes(array $value)
 * @method self setElement_hash(string $value)
 */
class passportElementError extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Error source, could be `data` and `front_side` and `reverse_side` and `selfie` and `file` and `files` and
     * `translation_file` and `translation_files` and `unspecified`
     */
    public string $source;

    /**
     * The section of the user's Telegram Passport which has the error {
     *
     * `data` could be `personal_details` and `passport` and `driver_license` and `identity_card` and `internal_passport` and `address`
     *
     * `front_side` could be `passport` and `driver_license` and `identity_card` and `internal_passport`
     *
     * `reverse_side` could be `driver_license` and `identity_card`
     *
     * `selfie` could be `passport` and `driver_license` and `identity_card` and `internal_passport`
     *
     * `file` and `files` could be `utility_bill` and `bank_statement` and `rental_agreement` and `passport_registration` and `temporary_registration`
     *
     * }
     *
     * Type of element of the user's Telegram Passport which has the issue
     *
     * `translation_file` and `translation_files` could be `passport` and `driver_license` and `identity_card` and
     * `internal_passport` and `utility_bill` and `bank_statement` and `rental_agreement` and `passport_registration` and `temporary_registration`
     */
    public string $type;

    /** `data` only. Name of the data field which has the error */
    public string $field_name;

    /** `data` only. Base64-encoded data hash */
    public string $data_hash;

    /** Error message */
    public string $message;

    /**
     * `front_side` and `reverse_side` and `selfie` and `file` and `translation_file` only. Base64-encoded file hash
     *
     * `front_side` : front side of doc
     *
     * `reverse_side` : reverse side of doc
     */
    public string $file_hash;

    /**
     * `files` and `translation_files` only. List of base64-encoded file hashes
     * @var string[]
     */
    public array $file_hashes;

    /** `unspecified` only. Base64-encoded element hash */
    public string $element_hash;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}