<?php

namespace BPT\types;

use CURLFile;
use stdClass;

/**
 * This object represents the content of a media message to be sent.
 * @method self setType(string $value)
 * @method self setMedia(string $value)
 * @method self setCaption(string $value)
 * @method self setParse_mode(string $value)
 * @method self setCaption_entities(array $value)
 * @method self setThumb(CURLFile|string $value)
 * @method self setWidth(int $value)
 * @method self setHeight(int $value)
 * @method self setDuration(int $value)
 * @method self setSupports_streaming(bool $value)
 * @method self setPerformer(string $value)
 * @method self setTitle(string $value)
 * @method self setDisable_content_type_detection(bool $value)
 */
class inputMedia extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['caption_entities' => 'BPT\types\messageEntity']];

    /** Type of the result could be `photo`, `video`, `animation`, `audio`, `document` */
    public string $type;
    /**
     * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP
     * URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new
     */
    public string $media;

    /** Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing */
    public null|string $caption = null;

    /** Optional. Mode for parsing entities in the photo caption. See formatting options for more details. */
    public null|string $parse_mode = null;

    /**
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public null|array $caption_entities = null;

    /**
     * all types except `photo`. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported
     * server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and
     * height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't
     * be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the
     * thumbnail was uploaded using multipart/form-data under <file_attach_name>.
     */
    public null|CURLFile|string $thumb = null;

    /** `video` and `animation` only. width */
    public null|int $width = null;

    /** `video` and `animation` only. Optional. height */
    public null|int $height = null;

    /** `video` and `animation` and `audio` only.  Optional. duration in seconds*/
    public null|int $duration = null;

    /** `video` only. Optional. Pass True, if the uploaded video is suitable for streaming */
    public null|bool $supports_streaming = null;

    /** `audio` only. Optional. Performer of the audio */
    public null|string $performer = null;

    /** `audio` only. Optional. Title of the audio */
    public null|string $title = null;

    /**
     * `document` only. Optional. Disables automatic server-side content type detection for files uploaded using multipart/form-data.
     * Always True, if the document is sent as part of an album.
     */
    public null|bool $disable_content_type_detection = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}