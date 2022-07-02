<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a video chat scheduled in the chat.
 */
class videoChatScheduled extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Point in time (Unix timestamp) when the video chat is supposed to be started by a chat administrator */
    public int $start_date;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
