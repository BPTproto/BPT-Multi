<?php

namespace BPT\types;

use BPT\telegram\telegram;
use BPT\tools\tools;
use stdClass;

/**
 * This object represents a voice note.
 */
class voice extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Duration of the audio in seconds as defined by sender */
    public int $duration;

    /** Optional. MIME type of the file as defined by sender */
    public null|string $mime_type = null;

    /**
     * Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have
     * difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit
     * integer or double-precision float type are safe for storing this value.
     */
    public null|int $file_size = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * download this file and save it in destination
     *
     * if destination doesn't set , it will save in `file_name` file
     *
     * It has 20MB download limit(same as telegram)
     *
     * e.g. => $voice->download();
     *
     * e.g. => $voice->download('test.ogg');
     *
     * @param string|null $destination destination for save the file
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null): bool|string {
        return telegram::downloadFile($destination ?? 'unknown.ogg',$this->file_id);
    }

    /**
     * Get download link of this file
     *
     * It does not bypass telegram limits(e.g: Download size limit in public bot api)
     *
     * @return string
     */
    public function link(): string {
        return telegram::fileLink($this->file_id);
    }

    public function typedSize (int $precision = 2, bool $space_between = true): string {
        return tools::byteFormat($this->file_id, $precision, $space_between);
    }
}
