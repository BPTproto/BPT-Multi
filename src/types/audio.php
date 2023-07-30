<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object represents an audio file to be treated as music by the Telegram clients.
 */
class audio extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['thumbnail' => 'BPT\types\photoSize'];

    /** Identifier for this file, which can be used to download or reuse the file */
    public string $file_id;

    /**
     * Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be
     * used to download or reuse the file.
     */
    public string $file_unique_id;

    /** Duration of the audio in seconds as defined by sender */
    public int $duration;

    /** Optional. Performer of the audio as defined by sender or by audio tags */
    public null|string $performer = null;

    /** Optional. Title of the audio as defined by sender or by audio tags */
    public null|string $title = null;

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

    /** Optional. Thumbnail of the album cover to which the music file belongs */
    public null|photoSize $thumbnail = null;


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
     * e.g. => $audio->download();
     *
     * e.g. => $audio->download('test.mp3');
     *
     * @param string|null $destination destination for save the file
     *
     * @return bool|string string will be returned when destination doesn't set
     */
    public function download(string|null $destination = null): bool|string {
        return telegram::downloadFile($destination ?? $this->file_name ?? 'unknown.mp3',$this->file_id);
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
}
