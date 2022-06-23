<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to a photo. By default, this photo will be sent by the user with optional caption.
 * Alternatively, you can use input_message_content to send a message with the specified content instead of the
 * photo.
 */
class inlineQueryResultPhoto extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['caption_entities' => 'BPT\types\messageEntity'],
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be photo */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB */
    public string $photo_url;

    /** URL of the thumbnail for the photo */
    public string $thumb_url;

    /** Optional. Width of the photo */
    public int $photo_width;

    /** Optional. Height of the photo */
    public int $photo_height;

    /** Optional. Title for the result */
    public string $title;

    /** Optional. Short description of the result */
    public string $description;

    /** Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the photo caption. See formatting options for more details. */
    public string $parse_mode;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $caption_entities;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the photo */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
