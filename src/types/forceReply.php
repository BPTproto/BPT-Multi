<?php

namespace BPT\types;

use stdClass;

/**
 * Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as
 * if the user has selected the bot's message and tapped 'Reply'). This can be extremely useful if you want to
 * create user-friendly step-by-step interfaces without having to sacrifice privacy mode.
 * @method self setForce_reply(bool $value)
 * @method self setInput_field_placeholder(string $value)
 * @method self setSelective(bool $value)
 */
class forceReply extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Shows reply interface to the user, as if they manually selected the bot's message and tapped 'Reply' */
    public bool $force_reply;

    /** Optional. The placeholder to be shown in the input field when the reply is active; 1-64 characters */
    public string $input_field_placeholder;

    /**
     * Optional. Use this parameter if you want to force reply from specific users only. Targets: 1) users that are
     * mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id),
     * sender of the original message.
     */
    public bool $selective;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
