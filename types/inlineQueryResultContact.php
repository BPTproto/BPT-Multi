<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a contact with a phone number. By default, this contact will be sent by the user. Alternatively,
 * you can use input_message_content to send a message with the specified content instead of the contact.
 */
class inlineQueryResultContact extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'input_message_content' => 'BPT\types\inputMessageContent',
    ];

    /** Type of the result, must be contact */
    public string $type;

    /** Unique identifier for this result, 1-64 Bytes */
    public string $id;

    /** Contact's phone number */
    public string $phone_number;

    /** Contact's first name */
    public string $first_name;

    /** Optional. Contact's last name */
    public string $last_name;

    /** Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes */
    public string $vcard;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** Optional. Content of the message to be sent instead of the contact */
    public inputMessageContent $input_message_content;

    /** Optional. Url of the thumbnail for the result */
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
