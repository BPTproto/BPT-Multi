<?php

namespace BPT\types;

use BPT\telegram\telegram;
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
    public null|int $file_size = null;


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
     * e.g. => $photo[0]->download();
     *
     * e.g. => $photo[0]->download('test.mp4');
     *
     * @param string|null $destination destination for save the file
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null): bool|string {
        return telegram::downloadFile($destination ?? 'unknown.jpg',$this->file_id);
    }

    public function link(): string {
        return telegram::fileLink($this->file_id);
    }
}
