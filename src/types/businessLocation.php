<?php

namespace BPT\types;

use stdClass;

/**
 * Contains information about the location of a Telegram Business account.
 */
class businessLocation extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['location' => 'BPT\types\location'];

    /** Address of the business */
    public string $address;

    /** Optional. Location of the business */
    public null|location $location = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
