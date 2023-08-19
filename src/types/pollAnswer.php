<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 */
class pollAnswer extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['voter_chat' => 'BPT\types\chat', 'user' => 'BPT\types\user'];

    /** Unique poll identifier */
    public string $poll_id;

    /** Optional. The chat that changed the answer to the poll, if the voter is anonymous */
    public chat $voter_chat;

    /** Optional. The user that changed the answer to the poll, if the voter isn't anonymous */
    public user $user;

    /**
     * 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
     * @var int[]
     */
    public array $option_ids;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
