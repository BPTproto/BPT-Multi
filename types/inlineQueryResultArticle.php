<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to an article or web page.
 */
class inlineQueryResultArticle extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'input_message_content' => 'BPT\types\inputMessageContent',
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
    ];

    /** Type of the result, must be article */
    public string $type;

    /** Unique identifier for this result, 1-64 Bytes */
    public string $id;

    /** Title of the result */
    public string $title;

    /** Content of the message to be sent */
    public inputMessageContent $input_message_content;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. URL of the result */
    public string $url;

    /** Optional. Pass True, if you don't want the URL to be shown in the message */
    public bool $hide_url;

    /** Optional. Short description of the result */
    public string $description;

    /** Optional. Url of the thumbnail for the result */
    public string $thumb_url;

    /** Optional. Thumbnail width */
    public int $thumb_width;

    /** Optional. Thumbnail height */
    public int $thumb_height;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
