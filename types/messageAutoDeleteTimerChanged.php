<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a change in auto-delete timer settings.
 */
class messageAutoDeleteTimerChanged extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** New auto-delete time for messages in the chat; in seconds */
    public int $message_auto_delete_time;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
