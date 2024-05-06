<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about one answer option in a poll.
 */
class pollOption extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['text_entities' => 'BPT\types\messageEntity']];

    /** Option text, 1-100 characters */
    public string $text;

    /**
     * Optional. Special entities that appear in the option text. Currently, only custom emoji entities are allowed
     * in poll option texts
     * @var messageEntity[]
     */
    public array $text_entities;

    /** Number of users that voted for this option */
    public int $voter_count;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
