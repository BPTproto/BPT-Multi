<?php

namespace BPT\types;

use stdClass;

/**
 * Describes data required for decrypting and authenticating EncryptedPassportElement. See the Telegram Passport
 * Documentation for a complete description of the data decryption and authentication processes.
 */
class encryptedCredentials extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Base64-encoded encrypted JSON-serialized data with unique user's payload, data hashes and secrets required for
     * EncryptedPassportElement decryption and authentication
     */
    public string $data;

    /** Base64-encoded data hash for data authentication */
    public string $hash;

    /** Base64-encoded secret, encrypted with the bot's public RSA key, required for data decryption */
    public string $secret;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
