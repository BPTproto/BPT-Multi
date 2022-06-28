<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a chat photo.
 */
class chatPhoto extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * File identifier of small (160x160) chat photo. This file_id can be used only for photo download and only for
     * as long as the photo is not changed.
     */
    public string $small_file_id;

    /**
     * Unique file identifier of small (160x160) chat photo, which is supposed to be the same over time and for
     * different bots. Can't be used to download or reuse the file.
     */
    public string $small_file_unique_id;

    /**
     * File identifier of big (640x640) chat photo. This file_id can be used only for photo download and only for as
     * long as the photo is not changed.
     */
    public string $big_file_id;

    /**
     * Unique file identifier of big (640x640) chat photo, which is supposed to be the same over time and for
     * different bots. Can't be used to download or reuse the file.
     */
    public string $big_file_unique_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
