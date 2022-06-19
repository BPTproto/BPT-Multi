<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a photo to be sent.
 */
class inputMediaPhoto extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Type of the result, must be photo */
    public string $type;

    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP
     * URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new
     * one using multipart/form-data under <file_attach_name> name. More info on Sending Files »
     */
    public string $media;

    /** Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing */
    public string $caption;

    /** Optional. Mode for parsing entities in the photo caption. See formatting options for more details. */
    public string $parse_mode;

    /** Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode */
    public array $caption_entities;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
