<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a location to which a chat is connected.
 */
class chatLocation extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['location' => 'BPT\types\location'];

    /** The location to which the supergroup is connected. Can't be a live location. */
    public location $location;

    /** Location address; 1-64 characters, as defined by the chat owner */
    public string $address;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
