<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a message about a forwarded story in the chat. Currently holds no information.
 */
class story extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat'];

    /** Chat that posted the story */
    public chat $chat;

    /** Unique identifier for the story in the chat */
    public int $id;

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
