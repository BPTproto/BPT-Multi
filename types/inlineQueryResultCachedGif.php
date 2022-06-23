<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to an animated GIF file stored on the Telegram servers. By default, this animated GIF file
 * will be sent by the user with an optional caption. Alternatively, you can use input_message_content to send a
 * message with specified content instead of the animation.
 */
class inlineQueryResultCachedGif extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['caption_entities' => 'BPT\types\messageEntity'],
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be gif */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** A valid file identifier for the GIF file */
    public string $gif_file_id;

    /** Optional. Title for the result */
    public string $title;

    /** Optional. Caption of the GIF file to be sent, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the caption. See formatting options for more details. */
    public string $parse_mode;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $caption_entities;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the GIF animation */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
