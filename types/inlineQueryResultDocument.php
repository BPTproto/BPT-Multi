<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to a file. By default, this file will be sent by the user with an optional caption.
 * Alternatively, you can use input_message_content to send a message with the specified content instead of the
 * file. Currently, only .PDF and .ZIP files can be sent using this method.
 */
class inlineQueryResultDocument extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['caption_entities' => 'BPT\types\messageEntity'],
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be document */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** Title for the result */
    public string $title;

    /** Optional. Caption of the document to be sent, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the document caption. See formatting options for more details. */
    public string $parse_mode;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $caption_entities;

    /** A valid URL for the file */
    public string $document_url;

    /** MIME type of the content of the file, either “application/pdf” or “application/zip” */
    public string $mime_type;

    /** Optional. Short description of the result */
    public string $description;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the file */
    public inputMessageContent $input_message_content;

    /** Optional. URL of the thumbnail (JPEG only) for the file */
    public string $thumb_url;

    /** Optional. Thumbnail width */
    public int $thumb_width;

    /** Optional. Thumbnail height */
    public int $thumb_height;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
