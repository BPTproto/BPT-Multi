<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the origin of a message.
 */
class messageOrigin extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['sender_user' => 'BPT\types\user', 'sender_chat' => 'BPT\types\chat', 'chat' => 'BPT\types\chat'];

    /** Could be "user" or "hidden_user" or "chat" or "channel" */
    public string $type;

    /** Date the message was sent originally in Unix time */
    public int $date;

    /** `user` only. User that sent the message originally */
    public null|user $sender_user = null;

    /** `hidden_user` only. Name of the user that sent the message originally */
    public null|string $sender_user_name = null;

    /** `chat` only. Chat that sent the message originally */
    public null|chat $sender_chat = null;

    /**
     * `chat` and `channel` only. Signature of the original post author, For messages originally sent by an anonymous
     * chat administrator, original message author signature
     */
    public null|string $author_signature = null;

    /** `channel` only. Channel chat to which the message was originally sent */
    public null|chat $chat = null;

    /** `channel` only. Unique message identifier inside the chat */
    public null|int $message_id = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}