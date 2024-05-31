<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one result of an inline query.
 * @method self setType(string $value)
 * @method self setId(string $value)
 * @method self setTitle(string $value)
 * @method self setInput_message_content(inputMessageContent $value)
 * @method self setReply_markup(inlineKeyboardMarkup $value)
 * @method self setUrl(string $value)
 * @method self setHide_url(bool $value)
 * @method self setDescription(string $value)
 * @method self setThumb_url(string $value)
 * @method self setThumb_width(int $value)
 * @method self setThumb_height(int $value)
 * @method self setPhoto_url(string $value)
 * @method self setPhoto_width(int $value)
 * @method self setPhoto_height(int $value)
 * @method self setCaption(string $value)
 * @method self setParse_mode(string $value)
 * @method self setCaption_entities(array $value)
 * @method self setGif_url(string $value)
 * @method self setGif_width(int $value)
 * @method self setGif_height(int $value)
 * @method self setGif_duration(int $value)
 * @method self setThumb_mime_type(string $value)
 * @method self setMpeg4_url(string $value)
 * @method self setMpeg4_width(int $value)
 * @method self setMpeg4_height(int $value)
 * @method self setMpeg4_duration(int $value)
 * @method self setVideo_url(string $value)
 * @method self setMime_type(string $value)
 * @method self setVideo_width(int $value)
 * @method self setVideo_height(int $value)
 * @method self setVideo_duration(int $value)
 * @method self setAudio_url(string $value)
 * @method self setPerformer(string $value)
 * @method self setAudio_duration(int $value)
 * @method self setVoice_url(string $value)
 * @method self setVoice_duration(int $value)
 * @method self setDocument_url(string $value)
 * @method self setLatitude(float $value)
 * @method self setLongitude(float $value)
 * @method self setHorizontal_accuracy(float $value)
 * @method self setLive_period(int $value)
 * @method self setHeading(int $value)
 * @method self setProximity_alert_radius(int $value)
 * @method self setAddress(string $value)
 * @method self setFoursquare_id(string $value)
 * @method self setFoursquare_type(string $value)
 * @method self setGoogle_place_id(string $value)
 * @method self setGoogle_place_type(string $value)
 * @method self setPhone_number(string $value)
 * @method self setFirst_name(string $value)
 * @method self setLast_name(string $value)
 * @method self setVcard(string $value)
 * @method self setGame_short_name(string $value)
 * @method self setPhoto_file_id(string $value)
 * @method self setGif_file_id(string $value)
 * @method self setMpeg4_file_id(string $value)
 * @method self setSticker_file_id(string $value)
 * @method self setDocument_file_id(string $value)
 * @method self setVideo_file_id(string $value)
 * @method self setVoice_file_id(string $value)
 * @method self setAudio_file_id(string $value)
 */
class inlineQueryResult extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'input_message_content' => 'BPT\types\inputMessageContent',
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
        'array' => ['caption_entities' => 'BPT\types\messageEntity'],
    ];

    /**
     * Type of the result, could be `article`, `sticker`, `location`, `venue`, `contact`, `game`,
     *
     * `photo`(photo and cashedPhoto),
     *
     * `gif`(gif and cashedGif),
     *
     * `mpeg4_gif`(mpeg4Gif and cachedMpeg4Gif),
     *
     * `video`(video and cashedVideo),
     *
     * `audio`(audio and cashedAudio),
     *
     * `voice`(voice and cashedVoice),
     *
     * `document`(document and cashedDocument)
     */
    public string $type;

    /** Unique identifier for this result, 1-64 Bytes */
    public string $id;

    /** all types except `contact` and `sticker` and `game` and `cashedAudio`. Title */
    public string $title;

    /** all types except `contact`. Content of the message to be sent */
    public inputMessageContent $input_message_content;

    /** Optional. Inline keyboard attached to the message */
    public inlineKeyboardMarkup $reply_markup;

    /** `article` only. Optional. URL of the result */
    public string $url;

    /** `article` only. Optional. Pass True, if you don't want the URL to be shown in the message */
    public bool $hide_url;

    /**
     * `article` and `photo` and `video` and `document` and `cachedPhoto` and `cachedDocument` and `cachedVideo` only.
     * Short description of the result
     */
    public string $description;

    /**
     * `article` and `photo` and `gif` and `mpeg4Gif` and `video` and `document` and `location` and `venue` and `contact` only.
     * Optional. Url of the thumbnail
     *
     * `gif` and `mpeg4Gif` could be jpeg or gif(fixed) or mpeg4(animate)
     *
     * `video` and `document` could be jpeg only
     */
    public string $thumbnail_url;

    /** `article` and `document` and `location` and `venue` and `contact` only. Optional. Thumbnail width */
    public int $thumbnail_width;

    /** `article` and `document` and `location` and `venue` and `contact` only. Optional. Thumbnail height */
    public int $thumbnail_height;

    /** `photo` only. A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB */
    public string $photo_url;

    /** `photo` only. Optional. Width of the photo */
    public int $photo_width;

    /** `photo` only. Optional. Height of the photo */
    public int $photo_height;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. Caption, 0-1024 characters after entities parsing
     */
    public string $caption;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. Mode for parsing entities in the caption. See formatting options for more details.
     */
    public string $parse_mode;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public array $caption_entities;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `cachedPhoto` and `cachedGif` and `cachedMpeg4Gif` and
     * `cachedVideo` only. Optional. Pass True, if the caption must be shown above the message media
     */
    public bool $show_caption_above_media;

    /** `gif` only. A valid URL for the GIF file. File size must not exceed 1MB */
    public string $gif_url;

    /** `gif` only. Optional. Width of the GIF */
    public int $gif_width;

    /** `gif` only. Optional. Height of the GIF */
    public int $gif_height;

    /** `gif` only. Optional. Duration of the GIF in seconds */
    public int $gif_duration;

    /** `gif` and `mpeg4Gif` only. could be `application/pdf` or `application/zip` or `video/mp4`. default : `image/jpeg` */
    public string $thumbnail_mime_type;

    /** `mpeg4Gif` only. A valid URL for the MPEG4 file. File size must not exceed 1MB */
    public string $mpeg4_url;

    /** `mpeg4Gif` only. Optional. Video width */
    public int $mpeg4_width;

    /** `mpeg4Gif` only. Optional. Video height */
    public int $mpeg4_height;

    /** `mpeg4Gif` only. Optional. Video duration in seconds */
    public int $mpeg4_duration;

    /** `video` only. A valid URL for the embedded video player or video file */
    public string $video_url;

    /**
     * `video` or `document` only. MIME type of the content
     *
     * `video` could be `text/html` or `video/mp4`
     *
     * `document` could be `application/pdf` or `application/zip`
     */
    public string $mime_type;

    /** `video` only. Optional. Video width */
    public int $video_width;

    /** `video` only. Optional. Video height */
    public int $video_height;

    /** `video` only. Optional. Video duration in seconds */
    public int $video_duration;

    /** `audio` only. A valid URL for the audio file */
    public string $audio_url;

    /** `audio` only. Optional. Performer */
    public string $performer;

    /** `audio` only. Optional. Audio duration in seconds */
    public int $audio_duration;

    /** `voice` only. A valid URL for the voice recording */
    public string $voice_url;

    /** `voice` only. Optional. Recording duration in seconds */
    public int $voice_duration;

    /** `document` only. A valid URL for the file */
    public string $document_url;

    /** `location` and `venue` only. latitude in degrees */
    public float $latitude;

    /** `location` and `venue` only. longitude in degrees */
    public float $longitude;

    /** `location` only. Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
    public float $horizontal_accuracy;

    /**
     * `location` only. Optional. Period in seconds during which the location can be updated, should be between 60 and 86400, or
     * 0x7FFFFFFF for live locations that can be edited indefinitely.
     */
    public int $live_period;

    /**
     * `location` only. Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360
     * if specified.
     */
    public int $heading;

    /**
     * `location` only. Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member,
     * in meters. Must be between 1 and 100000 if specified.
     */
    public int $proximity_alert_radius;

    /** `venue` only. Address of the venue */
    public string $address;

    /** `venue` only. Optional. Foursquare identifier of the venue if known */
    public string $foursquare_id;

    /**
     * `venue` only. Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public string $foursquare_type;

    /** `venue` only. Optional. Google Places identifier of the venue */
    public string $google_place_id;

    /** `venue` only. Optional. Google Places type of the venue. (See supported types.) */
    public string $google_place_type;

    /** `contact` only. Contact's phone number */
    public string $phone_number;

    /** `contact` only. Contact's first name */
    public string $first_name;

    /** `contact` only. Optional. Contact's last name */
    public string $last_name;

    /** `contact` only. Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes */
    public string $vcard;

    /** `game` only. Short name of the game */
    public string $game_short_name;

    /** `cachedPhoto` only. A valid file identifier of the photo */
    public string $photo_file_id;

    /** `cachedGif` only. A valid file identifier for the GIF file */
    public string $gif_file_id;

    /** `cachedMpeg4Gif` only. A valid file identifier for the MPEG4 file */
    public string $mpeg4_file_id;

    /** `cachedSticker` only. A valid file identifier of the sticker */
    public string $sticker_file_id;

    /** `cachedDocument` only. A valid file identifier for the file */
    public string $document_file_id;

    /** `cachedVideo` only. A valid file identifier for the video file */
    public string $video_file_id;

    /** `cachedVoice` only. A valid file identifier for the voice message */
    public string $voice_file_id;

    /** `cachedAudio` only. A valid file identifier for the audio file */
    public string $audio_file_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}