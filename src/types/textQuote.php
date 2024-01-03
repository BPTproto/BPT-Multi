<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about the quoted part of a message that is replied to by the given message.
 */
class textQuote extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['entities' => 'BPT\types\messageEntity']];

    /** Text of the quoted part of a message that is replied to by the given message */
    public string $text;

    /**
     * Optional. Special entities that appear in the quote. Currently, only bold, italic, underline, strikethrough,
     * spoiler, and custom_emoji entities are kept in quotes.
     * @var messageEntity[]
     */
    public null|array $entities = null;

    /** Approximate quote position in the original message in UTF-16 code units as specified by the sender */
    public int $position;

    /**
     * Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added
     * automatically by the server.
     */
    public null|bool $is_manual = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
