<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat, with
 * an optional default inline query.
 */
class switchInlineQueryChosenChat extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /**
     * Optional. The default inline query to be inserted in the input field. If left empty, only the bot's username
     * will be inserted
     */
    public string $query;

    /** Optional. True, if private chats with users can be chosen */
    public bool $allow_user_chats;

    /** Optional. True, if private chats with bots can be chosen */
    public bool $allow_bot_chats;

    /** Optional. True, if group and supergroup chats can be chosen */
    public bool $allow_group_chats;

    /** Optional. True, if channel chats can be chosen */
    public bool $allow_channel_chats;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
