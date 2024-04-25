<?php

namespace BPT\types;

use stdClass;

/**
 * Describes documents or other Telegram Passport elements shared with the bot by the user.
 */
class encryptedPassportElement extends types {
    /** Keep all properties which has sub properties */
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
     * Optional. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for
     * “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and
     * “address” types. Can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public null|string $data = null;

    /** Optional. User's verified phone number, available only for “phone_number” type */
    public null|string $phone_number = null;

    /** Optional. User's verified email address, available only for “email” type */
    public null|string $email = null;

    /**
     * Optional. Array of encrypted files with documents provided by the user; available only for “utility_bill”,
     * “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration”
     * types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
     * @var passportFile[]
     */
    public null|array $files = null;

    /**
     * Optional. Encrypted file with the front side of the document, provided by the user; available only for
     * “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be
     * decrypted and verified using the accompanying EncryptedCredentials.
     */
    public null|passportFile $front_side = null;

    /**
     * Optional. Encrypted file with the reverse side of the document, provided by the user; available only for
     * “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying
     * EncryptedCredentials.
     */
    public null|passportFile $reverse_side = null;

    /**
     * Optional. Encrypted file with the selfie of the user holding a document, provided by the user; available if
     * requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file
     * can be decrypted and verified using the accompanying EncryptedCredentials.
     */
    public null|passportFile $selfie = null;

    /**
     * Optional. Array of encrypted files with translated versions of documents provided by the user; available if
     * requested for “passport”, “driver_license”, “identity_card”, “internal_passport”,
     * “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and
     * “temporary_registration” types. Files can be decrypted and verified using the accompanying
     * EncryptedCredentials.
     * @var passportFile[]
     */
    public null|array $translation = null;

    /** Base64-encoded element hash for using in PassportElementErrorUnspecified */
    public string $hash;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
