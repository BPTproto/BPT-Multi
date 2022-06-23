<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to a voice message stored on the Telegram servers. By default, this voice message will be
 * sent by the user. Alternatively, you can use input_message_content to send a message with the specified
 * content instead of the voice message.
 */
class inlineQueryResultCachedVoice extends types {
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

    /** A valid file identifier for the voice message */
    public string $voice_file_id;

    /** Voice message title */
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

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the voice message */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
