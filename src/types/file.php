<?php

namespace BPT\types;

use BPT\settings;
use stdClass;

/**
 * This object represents a file ready to be downloaded. The file can be downloaded via the link
 * https://api.telegram.org/file/bot<token>/<file_path>. It is guaranteed that the link will be valid for at
 * least 1 hour. When the link expires, a new one can be requested by calling getFile.
 */
class file extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $id;

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /**
     * Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have
     * difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit
     * integer or double-precision float type are safe for storing this value.
     */
    public null|int $file_size = null;

    /** Optional. File path. Use https://api.telegram.org/file/bot<token>/<file_path> to get the file. */
    public null|string $file_path = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Get download link of this file
     *
     * It does not bypass telegram limits(e.g: Download size limit in public bot api)
     *
     * @return string
     */
    public function link(): string {
        return settings::$down_url . '/bot' . settings::$token . '/' . $this->file_path;
    }
}
