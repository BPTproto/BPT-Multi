<?php

namespace BPT\types;
use stdClass;

class maybeInaccessibleMessage extends message {
    /** Chat the message belonged to */
    public chat $chat;

    /** Unique message identifier inside the chat */
    public int $message_id;
    /**
     * Date the message was sent in Unix time. It is always a positive number, representing a valid date.
     * Always 0 in inaccessible messages.
     */
    public int $date;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object);
        }
    }
}