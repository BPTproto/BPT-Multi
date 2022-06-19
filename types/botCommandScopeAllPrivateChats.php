<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the scope of bot commands, covering all private chats.
 */
class botCommandScopeAllPrivateChats extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Scope type, must be all_private_chats */
    public string $type;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
