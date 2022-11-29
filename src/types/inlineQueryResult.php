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
    public null|string $title = null;

    /** all types except `contact`. Content of the message to be sent */
    public null|inputMessageContent $input_message_content = null;

    /** Optional. Inline keyboard attached to the message */
    public null|inlineKeyboardMarkup $reply_markup = null;

    /** `article` only. Optional. URL of the result */
    public null|string $url = null;

    /** `article` only. Optional. Pass True, if you don't want the URL to be shown in the message */
    public null|bool $hide_url = null;

    /**
     * `article` and `photo` and `video` and `document` and `cachedPhoto` and `cachedDocument` and `cachedVideo` only.
     * Short description of the result
     */
    public null|string $description = null;

    /**
     * `article` and `photo` and `gif` and `mpeg4Gif` and `video` and `document` and `location` and `venue` and `contact` only.
     * Optional. Url of the thumbnail
     *
     * `gif` and `mpeg4Gif` could be jpeg or gif(fixed) or mpeg4(animate)
     *
     * `video` and `document` could be jpeg only
     */
    public null|string $thumb_url = null;

    /** `article` and `document` and `location` and `venue` and `contact` only. Optional. Thumbnail width */
    public null|int $thumb_width = null;

    /** `article` and `document` and `location` and `venue` and `contact` only. Optional. Thumbnail height */
    public null|int $thumb_height = null;

    /** `photo` only. A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB */
    public null|string $photo_url = null;

    /** `photo` only. Optional. Width of the photo */
    public null|int $photo_width = null;

    /** `photo` only. Optional. Height of the photo */
    public null|int $photo_height = null;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. Caption, 0-1024 characters after entities parsing
     */
    public null|string $caption = null;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. Mode for parsing entities in the caption. See formatting options for more details.
     */
    public null|string $parse_mode = null;

    /**
     * `photo` and `gif` and `mpeg4Gif` and `video` and `audio` and `voice` and `document` and `cachedPhoto` and
     * `cachedGif` and `cachedMpeg4Gif` and `cachedDocument` and `cachedVideo` and `cachedVoice` and `cachedAudio` only.
     * Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public null|array $caption_entities = null;

    /** `gif` only. A valid URL for the GIF file. File size must not exceed 1MB */
    public null|string $gif_url = null;

    /** `gif` only. Optional. Width of the GIF */
    public null|int $gif_width = null;

    /** `gif` only. Optional. Height of the GIF */
    public null|int $gif_height = null;

    /** `gif` only. Optional. Duration of the GIF in seconds */
    public null|int $gif_duration = null;

    /** `gif` and `mpeg4Gif` only. could be `application/pdf` or `application/zip` or `video/mp4`. default : `image/jpeg` */
    public null|string $thumb_mime_type = null;

    /** `mpeg4Gif` only. A valid URL for the MPEG4 file. File size must not exceed 1MB */
    public null|string $mpeg4_url = null;

    /** `mpeg4Gif` only. Optional. Video width */
    public null|int $mpeg4_width = null;

    /** `mpeg4Gif` only. Optional. Video height */
    public null|int $mpeg4_height = null;

    /** `mpeg4Gif` only. Optional. Video duration in seconds */
    public null|int $mpeg4_duration = null;

    /** `video` only. A valid URL for the embedded video player or video file */
    public null|string $video_url = null;

    /**
     * `video` or `document` only. MIME type of the content
     *
     * `video` could be `text/html` or `video/mp4`
     *
     * `document` could be `application/pdf` or `application/zip`
     */
    public null|string $mime_type = null;

    /** `video` only. Optional. Video width */
    public null|int $video_width = null;

    /** `video` only. Optional. Video height */
    public null|int $video_height = null;

    /** `video` only. Optional. Video duration in seconds */
    public null|int $video_duration = null;

    /** `audio` only. A valid URL for the audio file */
    public null|string $audio_url = null;

    /** `audio` only. Optional. Performer */
    public null|string $performer = null;

    /** `audio` only. Optional. Audio duration in seconds */
    public null|int $audio_duration = null;

    /** `voice` only. A valid URL for the voice recording */
    public null|string $voice_url = null;

    /** `voice` only. Optional. Recording duration in seconds */
    public null|int $voice_duration = null;

    /** `document` only. A valid URL for the file */
    public null|string $document_url = null;

    /** `location` and `venue` only. latitude in degrees */
    public null|float $latitude = null;

    /** `location` and `venue` only. longitude in degrees */
    public null|float $longitude = null;

    /** `location` only. Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
    public null|float $horizontal_accuracy = null;

    /** `location` only. Optional. Period in seconds for which the location can be updated, should be between 60 and 86400. */
    public null|int $live_period = null;

    /**
     * `location` only. Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360
     * if specified.
     */
    public null|int $heading = null;

    /**
     * `location` only. Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member,
     * in meters. Must be between 1 and 100000 if specified.
     */
    public null|int $proximity_alert_radius = null;

    /** `venue` only. Address of the venue */
    public null|string $address = null;

    /** `venue` only. Optional. Foursquare identifier of the venue if known */
    public null|string $foursquare_id = null;

    /**
     * `venue` only. Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public null|string $foursquare_type = null;

    /** `venue` only. Optional. Google Places identifier of the venue */
    public null|string $google_place_id = null;

    /** `venue` only. Optional. Google Places type of the venue. (See supported types.) */
    public null|string $google_place_type = null;

    /** `contact` only. Contact's phone number */
    public null|string $phone_number = null;

    /** `contact` only. Contact's first name */
    public null|string $first_name = null;

    /** `contact` only. Optional. Contact's last name */
    public null|string $last_name = null;

    /** `contact` only. Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes */
    public null|string $vcard = null;

    /** `game` only. Short name of the game */
    public null|string $game_short_name = null;

    /** `cachedPhoto` only. A valid file identifier of the photo */
    public null|string $photo_file_id = null;

    /** `cachedGif` only. A valid file identifier for the GIF file */
    public null|string $gif_file_id = null;

    /** `cachedMpeg4Gif` only. A valid file identifier for the MPEG4 file */
    public null|string $mpeg4_file_id = null;

    /** `cachedSticker` only. A valid file identifier of the sticker */
    public null|string $sticker_file_id = null;

    /** `cachedDocument` only. A valid file identifier for the file */
    public null|string $document_file_id = null;

    /** `cachedVideo` only. A valid file identifier for the video file */
    public null|string $video_file_id = null;

    /** `cachedVoice` only. A valid file identifier for the voice message */
    public null|string $voice_file_id = null;

    /** `cachedAudio` only. A valid file identifier for the audio file */
    public null|string $audio_file_id = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}