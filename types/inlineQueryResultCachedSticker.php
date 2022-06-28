<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to a sticker stored on the Telegram servers. By default, this sticker will be sent by the
 * user. Alternatively, you can use input_message_content to send a message with the specified content instead of
 * the sticker.
 */
class inlineQueryResultCachedSticker extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be sticker */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** A valid file identifier of the sticker */
    public string $sticker_file_id;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the sticker */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
