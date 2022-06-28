<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the scope of bot commands, covering all administrators of a specific group or supergroup chat.
 */
class botCommandScopeChatAdministrators extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Scope type, must be chat_administrators */
    public string $type;

    /** Unique identifier for the target chat or username of the target supergroup (in the format supergroupusername) */
    public int $chat_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
