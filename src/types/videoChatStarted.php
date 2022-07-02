<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a video chat started in the chat. Currently holds no
 * information.
 */
class videoChatStarted extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
