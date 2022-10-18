<?php

namespace BPT\types;

use BPT\api\telegram;
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

    /**
     * Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”. The type of the sticker is
     * independent from its format, which is determined by the fields is_animated and is_video.
     */
    public string $type;

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

    /** Optional. For custom emoji stickers, unique identifier of the custom emoji */
    public string $custom_emoji_id;

    /** Optional. File size in bytes */
    public int $file_size;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * download this file and save it in destination
     *
     * if destination doesn't set , it will return the downloaded file(as string)
     *
     * It has 20MB download limit(same as telegram)
     *
     * e.g. => $sticker->download();
     *
     * e.g. => $sticker->download('test.png');
     *
     * @param string|null $destination destination for save the file
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null): bool|string {
        return telegram::downloadFile($destination ?? 'unknown.png',$this->file_id);
    }
}
