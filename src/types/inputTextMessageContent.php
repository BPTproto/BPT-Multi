<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the content of a text message to be sent as the result of an inline query.
 */
class inputTextMessageContent extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'array' => ['entities' => 'BPT\types\messageEntity'],
        'link_preview_options' => 'BPT\types\linkPreviewOptions',
    ];

    /** Text of the message to be sent, 1-4096 characters */
    public string $message_text;

    /** Optional. Mode for parsing entities in the message text. See formatting options for more details. */
    public null|string $parse_mode = null;

    /**
     * Optional. List of special entities that appear in message text, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public null|array $entities = null;

    /** Optional. Link preview generation options for the message */
    public null|linkPreviewOptions $link_preview_options = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
