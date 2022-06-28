<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to a voice recording in an .OGG container encoded with OPUS. By default, this voice
 * recording will be sent by the user. Alternatively, you can use input_message_content to send a message with
 * the specified content instead of the the voice message.
 */
class inlineQueryResultVoice extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['caption_entities' => 'BPT\types\messageEntity'],
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be voice */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** A valid URL for the voice recording */
    public string $voice_url;

    /** Recording title */
    public string $title;

    /** Optional. Caption, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the voice message caption. See formatting options for more details. */
    public string $parse_mode;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $caption_entities;

    /** Optional. Recording duration in seconds */
    public int $voice_duration;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the voice recording */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
