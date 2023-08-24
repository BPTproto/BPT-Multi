<?php

namespace BPT\types;

use BPT\constants\fields;
use BPT\telegram\request;
use stdClass;

/**
 * This object represents a phone contact.
 */
class contact extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Contact's phone number */
    public string $phone_number;

    /** Contact's first name */
    public string $first_name;

    /** Optional. Contact's last name */
    public null|string $last_name = null;

    /**
     * Optional. Contact's user identifier in Telegram. This number may have more than 32 significant bits and some
     * programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant
     * bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public null|int $user_id = null;

    /** Optional. Additional data about the contact in the form of a vCard */
    public null|string $vcard = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Check if shared contact is for the sender user (useful for phone auth)
     *
     * @return bool
     */
    public function isUserPhone(): bool {
        return $this->user_id === request::catchFields(fields::USER_ID);
    }
}
