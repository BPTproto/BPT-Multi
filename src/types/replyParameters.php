<?php

namespace BPT\types;

use stdClass;

/**
 * Describes reply parameters for the message that is being sent.
 */
class replyParameters extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['quote_entities' => 'BPT\types\messageEntity']];

    /**
     * Identifier of the message that will be replied to in the current chat, or in the chat chat_id if it is
     * specified
     */
    public null|int $message_id;

    /**
     * Optional. If the message to be replied to is from a different chat, unique identifier for the chat or username
     * of the channel (in the format channelusername). Not supported for messages sent on behalf of a business
     * account.
     */
    public null|int $chat_id = null;

    /**
     * Optional. Pass True if the message should be sent even if the specified message to be replied to is not found.
     * Always False for replies in another chat or forum topic. Always True for messages sent on behalf of a business
     * account.
     */
    public null|bool $allow_sending_without_reply = null;

    /**
     * Optional. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote
     * must be an exact substring of the message to be replied to, including bold, italic, underline, strikethrough,
     * spoiler, and custom_emoji entities. The message will fail to send if the quote isn't found in the original
     * message.
     */
    public null|string $quote = null;

    /** Optional. Mode for parsing entities in the quote. See formatting options for more details. */
    public null|string $quote_parse_mode = null;

    /**
     * Optional. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of
     * quote_parse_mode.
     * @var messageEntity[]
     */
    public null|array $quote_entities = null;

    /** Optional. Position of the quote in the original message in UTF-16 code units */
    public null|int $quote_position = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
