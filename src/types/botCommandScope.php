<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the scope to which bot commands are applied.
 * @method self setType(string $value)
 * @method self setChat_id(int $value)
 * @method self setUser_id(int $value)
 */
class botCommandScope extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Scope type, could be `default`, `all_private_chats`, `all_group_chats`, `all_chat_administrators`, `chat`, `chat_administrators`, `chat_member`
     */
    public string $type;

    /**
     * `chat` and `chat_administrators` and `chat_member` only. Unique identifier for the target chat or username of the target supergroup (in the format supergroupusername)
     */
    public int $chat_id;

    /** `chat_member` only. Unique identifier of the target user */
    public int $user_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}