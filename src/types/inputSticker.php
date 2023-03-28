<?php

namespace BPT\types;

use CURLFile;
use stdClass;

/**
 * This object describes a sticker to be added to a sticker set.
 */
class inputSticker extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['sticker' => 'BPT\types\inputFile', 'mask_position' => 'BPT\types\maskPosition'];

    /**
     * The added sticker. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass
     * an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using
     * multipart/form-data. Animated and video stickers can't be uploaded via HTTP URL. More information on Sending
     * Files »
     */
    public string|CURLFile $sticker;

    /**
     * List of 1-20 emoji associated with the sticker
     * @var string[]
     */
    public array $emoji_list;

    /** Optional. Position where the mask should be placed on faces. For “mask” stickers only. */
    public maskPosition $mask_position;

    /**
     * Optional. List of 0-20 search keywords for the sticker with total length of up to 64 characters. For
     * “regular” and “custom_emoji” stickers only.
     * @var string[]
     */
    public array $keywords;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
