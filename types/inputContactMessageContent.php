<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the content of a contact message to be sent as the result of an inline query.
 */
class inputContactMessageContent extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Contact's phone number */
    public string $phone_number;

    /** Contact's first name */
    public string $first_name;

    /** Optional. Contact's last name */
    public string $last_name;

    /** Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes */
    public string $vcard;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
