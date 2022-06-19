<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a link to an MP3 audio file stored on the Telegram servers. By default, this audio file will be
 * sent by the user. Alternatively, you can use input_message_content to send a message with the specified
 * content instead of the audio.
 */
class inlineQueryResultCachedAudio extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be audio */
    public string $type;

    /** Unique identifier for this result, 1-64 bytes */
    public string $id;

    /** A valid file identifier for the audio file */
    public string $audio_file_id;

    /** Optional. Caption, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the audio caption. See formatting options for more details. */
    public string $parse_mode;

    /** Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode */
    public array $caption_entities;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the audio */
    public inputMessageContent $input_message_content;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
