<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a video chat ended in the chat.
 */
class videoChatEnded extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Video chat duration in seconds */
    public int $duration;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
