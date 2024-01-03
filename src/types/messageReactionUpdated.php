<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a change of a reaction on a message performed by a user.
 */
class messageReactionUpdated extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'chat' => 'BPT\types\chat',
        'user' => 'BPT\types\user',
        'actor_chat' => 'BPT\types\chat',
        'array' => ['old_reaction' => 'BPT\types\reactionType', 'new_reaction' => 'BPT\types\reactionType'],
    ];

    /** The chat containing the message the user reacted to */
    public chat $chat;

    /** Unique identifier of the message inside the chat */
    public int $message_id;

    /** Optional. The user that changed the reaction, if the user isn't anonymous */
    public null|user $user = null;

    /** Optional. The chat on behalf of which the reaction was changed, if the user is anonymous */
    public null|chat $actor_chat = null;

    /** Date of the change in Unix time */
    public int $date;

    /**
     * Previous list of reaction types that were set by the user
     * @var reactionType[]
     */
    public array $old_reaction;

    /**
     * New list of reaction types that have been set by the user
     * @var reactionType[]
     */
    public array $new_reaction;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
