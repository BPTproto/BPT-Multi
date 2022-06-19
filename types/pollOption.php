<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about one answer option in a poll.
 */
class pollOption extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Option text, 1-100 characters */
    public string $text;

    /** Number of users that voted for this option */
    public int $voter_count;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
