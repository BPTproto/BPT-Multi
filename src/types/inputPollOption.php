<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about one answer option in a poll to send.
 */
class inputPollOption extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['text_entities' => 'BPT\types\messageEntity']];

    /** Option text, 1-100 characters */
    public string|null $text = null;

    /**
     * Optional. Mode for parsing entities in the text. See formatting options for more details. Currently, only
     * custom emoji entities are allowed
     */
    public string|null $text_parse_mode = null;

    /**
     * Optional. A JSON-serialized list of special entities that appear in the poll option text. It can be specified
     * instead of text_parse_mode
     * @var messageEntity[]
     */
    public array|null $text_entities = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
