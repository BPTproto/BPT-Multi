<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a sticker.
 */
class sticker extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'thumb' => 'BPT\types\photoSize',
        'premium_animation' => 'BPT\types\file',
        'mask_position' => 'BPT\types\maskPosition',
    ];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Sticker width */
    public int $width;

    /** Sticker height */
    public int $height;

    /** True, if the sticker is animated */
    public bool $is_animated;

    /** True, if the sticker is a video sticker */
    public bool $is_video;

    /** Optional. Sticker thumbnail in the .WEBP or .JPG format */
    public photoSize $thumb;

    /** Optional. Emoji associated with the sticker */
    public string $emoji;

    /** Optional. Name of the sticker set to which the sticker belongs */
    public string $set_name;

    /** Optional. Premium animation for the sticker, if the sticker is premium */
    public file $premium_animation;

    /** Optional. For mask stickers, the position where the mask should be placed */
    public maskPosition $mask_position;

    /** Optional. File size in bytes */
    public int $file_size;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
