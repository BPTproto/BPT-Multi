<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 */
class messageReactionCountUpdated extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat', 'array' => ['reactions' => 'BPT\types\reactionCount']];

    /** The chat containing the message */
    public chat $chat;

    /** Unique message identifier inside the chat */
    public int $message_id;

    /** Date of the change in Unix time */
    public int $date;

    /**
     * List of reactions that are present on the message
     * @var reactionCount[]
     */
    public array $reactions;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
