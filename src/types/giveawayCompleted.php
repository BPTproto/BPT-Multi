<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 */
class giveawayCompleted extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['giveaway_message' => 'BPT\types\message'];

    /** Number of winners in the giveaway */
    public int $winner_count;

    /** Optional. Number of undistributed prizes */
    public null|int $unclaimed_prize_count = null;

    /** Optional. Message with the giveaway that was completed, if it wasn't deleted */
    public null|message $giveaway_message = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
