<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a sticker set.
 */
class stickerSet extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['stickers' => 'BPT\types\sticker'], 'thumbnail' => 'BPT\types\photoSize'];

    /** Sticker set name */
    public string $name;

    /** Sticker set title */
    public string $title;

    /** Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji” */
    public string $sticker_type;

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
