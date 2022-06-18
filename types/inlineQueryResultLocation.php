<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a location on a map. By default, the location will be sent by the user. Alternatively, you can use
 * input_message_content to send a message with the specified content instead of the location.
 */
class inlineQueryResultLocation extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [
		'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
		'input_message_content' => 'BPT\types\inputMessageContent',
	];

	/** Type of the result, must be location */
	public string $type;

	/** Unique identifier for this result, 1-64 Bytes */
	public string $id;

	/** Location latitude in degrees */
	public float $latitude;

	/** Location longitude in degrees */
	public float $longitude;

	/** Location title */
	public string $title;

	/** Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
	public float $horizontal_accuracy;

	/** Optional. Period in seconds for which the location can be updated, should be between 60 and 86400. */
	public int $live_period;

	/**
	 * Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360
	 * if specified.
	 */
	public int $heading;

	/**
	 * Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member,
	 * in meters. Must be between 1 and 100000 if specified.
	 */
	public int $proximity_alert_radius;

	/** Optional. Inline keyboard attached to the message */
	public inlineKeyboardMarkup $reply_markup;

	/** Optional. Content of the message to be sent instead of the location */
	public inputMessageContent $input_message_content;

	/** Optional. Url of the thumbnail for the result */
	public string $thumb_url;

	/** Optional. Thumbnail width */
	public int $thumb_width;

	/** Optional. Thumbnail height */
	public int $thumb_height;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
