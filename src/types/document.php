<?php

namespace BPT\types;

use BPT\api\telegram;
use stdClass;

/**
 * This object represents a general file (as opposed to photos, voice messages and audio files).
 */
class document extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['thumb' => 'BPT\types\photoSize'];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Optional. Document thumbnail as defined by sender */
    public null|photoSize $thumb = null;

    /** Optional. Original filename as defined by sender */
    public null|string $file_name = null;

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
     * e.g. => $document->download();
     *
     * e.g. => $document->download('test.zip');
     *
     * @param string|null $destination destination for save the file
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null): bool|string {
        return telegram::downloadFile($destination ?? $this->file_name ?? 'unknown.txt',$this->file_id);
    }
}
