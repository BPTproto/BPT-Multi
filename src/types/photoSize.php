<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one size of a photo or a file / sticker thumbnail.
 */
class photoSize extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Photo width */
    public int $width;

    /** Photo height */
    public int $height;

    /** Optional. File size in bytes */
    public int $file_size;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
