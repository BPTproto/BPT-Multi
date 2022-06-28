<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the scope of bot commands, covering all group and supergroup chat administrators.
 */
class botCommandScopeAllChatAdministrators extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Scope type, must be all_chat_administrators */
    public string $type;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
