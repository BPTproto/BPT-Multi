<?php

namespace BPT\types;

use stdClass;

/**
 * Describes documents or other Telegram Passport elements shared with the bot by the user.
 */
class encryptedPassportElement extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['files' => 'BPT\types\passportFile', 'translation' => 'BPT\types\passportFile'],
        'front_side' => 'BPT\types\passportFile',
        'reverse_side' => 'BPT\types\passportFile',
        'selfie' => 'BPT\types\passportFile',
    ];

    /**
     * Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”,
     * “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”,
     * “passport_registration”, “temporary_registration”, “phone_number”, “email”.
     */
    public string $type;

    /**
     * Optional. Base64-encoded encrypted Telegram Passport element data provided by the user, available for
     * “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and
     * “address” types. Can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public string $data;

    /** Optional. User's verified phone number, available only for “phone_number” type */
    public string $phone_number;

    /** Optional. User's verified email address, available only for “email” type */
    public string $email;

    /**
     * Optional. Array of encrypted files with documents provided by the user, available for “utility_bill”,
     * “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration”
     * types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     * @var passportFile[]
     */
    public array $files;

    /**
     * Optional. Encrypted file with the front side of the document, provided by the user. Available for
     * “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be
     * decrypted and verified using the accompanying EncryptedCredentials.
     */
    public passportFile $front_side;

    /**
     * Optional. Encrypted file with the reverse side of the document, provided by the user. Available for
     * “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying
     * EncryptedCredentials.
     */
    public passportFile $reverse_side;

    /**
     * Optional. Encrypted file with the selfie of the user holding a document, provided by the user; available for
     * “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be
     * decrypted and verified using the accompanying EncryptedCredentials.
     */
    public passportFile $selfie;

    /**
     * Optional. Array of encrypted files with translated versions of documents provided by the user. Available if
     * requested for “passport”, “driver_license”, “identity_card”, “internal_passport”,
     * “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and
     * “temporary_registration” types. Files can be decrypted and verified using the accompanying
     * EncryptedCredentials.
     * @var passportFile[]
     */
    public array $translation;

    /** Base64-encoded element hash for using in PassportElementErrorUnspecified */
    public string $hash;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
