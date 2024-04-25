<?php

namespace BPT\types;

use CURLFile;
use stdClass;

/**
 * This object describes a sticker to be added to a sticker set.
 *
 *
 * @method self setSticker(string|CURLFile $value)
 * @method self setFormat(string $value)
 * @method self setEmoji_list(string[] $value)
 * @method self setMask_position(maskPosition $value)
 * @method self setKeywords(string[] $value)
 */
class inputSticker extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['sticker' => 'CURLFile', 'mask_position' => 'BPT\types\maskPosition'];

    /**
     * The added sticker. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass
     * an HTTP URL as a String for Telegram to get a file from the Internet, upload a new one using
     * multipart/form-data, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data
     * under <file_attach_name> name. Animated and video stickers can't be uploaded via HTTP URL. More information on
     * Sending Files »
     */
    public string|CURLFile $sticker;

    /**
     * Format of the added sticker, must be one of “static” for a .WEBP or .PNG image, “animated” for a .TGS
     * animation, “video” for a WEBM video
     */
    public string $format;

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
