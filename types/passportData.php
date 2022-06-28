<?php

namespace BPT\types;

use stdClass;

/**
 * Describes Telegram Passport data shared with the bot by the user.
 */
class passportData extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['data' => 'BPT\types\encryptedPassportElement'],
        'credentials' => 'BPT\types\encryptedCredentials',
    ];

    /**
     * Array with information about documents and other Telegram Passport elements that was shared with the bot
     * @var encryptedPassportElement[]
     */
    public array $data;

    /** Encrypted credentials required to decrypt the data */
    public encryptedCredentials $credentials;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
