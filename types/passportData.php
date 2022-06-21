<?php

namespace BPT\types;

use stdClass;

/**
 * Describes Telegram Passport data shared with the bot by the user.
 */
class passportData extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['credentials' => 'BPT\types\encryptedCredentials'];

    /** Array with information about documents and other Telegram Passport elements that was shared with the bot */
    public array $data;

    /** Encrypted credentials required to decrypt the data */
    public encryptedCredentials $credentials;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
