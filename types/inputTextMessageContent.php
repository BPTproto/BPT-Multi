<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the content of a text message to be sent as the result of an inline query.
 */
class inputTextMessageContent extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['entities' => 'BPT\types\messageEntity']];

    /** Text of the message to be sent, 1-4096 characters */
    public string $message_text;

    /** Optional. Mode for parsing entities in the message text. See formatting options for more details. */
    public string $parse_mode;

    /**
     * Optional. List of special entities that appear in message text, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $entities;

    /** Optional. Disables link previews for links in the sent message */
    public bool $disable_web_page_preview;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
