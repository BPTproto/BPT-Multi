<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a sticker set.
 */
class stickerSet extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['stickers' => 'BPT\types\sticker'], 'thumb' => 'BPT\types\photoSize'];

    /** Sticker set name */
    public string $name;

    /** Sticker set title */
    public string $title;

    /** Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji” */
    public string $sticker_type;

    /** True, if the sticker set contains animated stickers */
    public null|bool $is_animated = null;

    /** True, if the sticker set contains video stickers */
    public null|bool $is_video = null;

    /** Deprecated use sticker_type instead, True, if the sticker set contains masks */
    public null|bool $contains_masks = null;

    /**
     * List of all set stickers
     * @var sticker[]
     */
    public array $stickers;

    /** Optional. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format */
    public null|photoSize $thumbnail = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
