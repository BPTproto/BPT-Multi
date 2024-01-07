<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 */
class reactionCount extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['type' => 'BPT\types\reactionType'];

    /** Type of the reaction */
    public reactionType $type;

    /** Number of times the reaction was added */
    public int $total_count;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
