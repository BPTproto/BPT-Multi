<?php

namespace BPT\types;

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
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Optional. File size in bytes, if known */
    public int $file_size;

    /** Optional. File path. Use https://api.telegram.org/file/bot<token>/<file_path> to get the file. */
    public string $file_path;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
