<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 */
class pollAnswer extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** Unique poll identifier */
    public string $poll_id;

    /** The user, who changed the answer to the poll */
    public user $user;

    /**
     * 0-based identifiers of answer options, chosen by the user. May be empty if the user retracted their vote.
     * @var int[]
     */
    public array $option_ids;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
