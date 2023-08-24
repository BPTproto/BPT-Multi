<?php

namespace BPT\types;

use BPT\telegram\telegram;
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

    /**
     * download this file and save it in destination
     *
     * if destination doesn't set , it will return the downloaded file(as string)
     *
     * It has 20MB download limit(same as telegram)
     *
     * e.g. => $photo->download();
     *
     * e.g. => $photo->download('test.mp4');
     *
     * @param string|null $destination destination for save the file
     * @param bool $big select big or small photo to download
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null,bool $big = true): bool|string {
        return telegram::downloadFile($destination ?? $this->file_name ?? 'unknown.mp4',$big ? $this->big_file_id : $this->small_file_id);
    }

    /**
     * Get download link of this file
     *
     * It does not bypass telegram limits(e.g: Download size limit in public bot api)
     *
     * @param bool $big
     *
     * @return string
     */
    public function link(bool $big = true): string {
        return telegram::fileLink($big ? $this->big_file_id : $this->small_file_id);
    }
}
