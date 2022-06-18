<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a point on the map.
 */
class location extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** Longitude as defined by sender */
	public float $longitude;

	/** Latitude as defined by sender */
	public float $latitude;

	/** Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
	public float $horizontal_accuracy;

	/**
	 * Optional. Time relative to the message sending date, during which the location can be updated; in seconds. For
	 * active live locations only.
	 */
	public int $live_period;

	/** Optional. The direction in which user is moving, in degrees; 1-360. For active live locations only. */
	public int $heading;

	/**
	 * Optional. Maximum distance for proximity alerts about approaching another chat member, in meters. For sent
	 * live locations only.
	 */
	public int $proximity_alert_radius;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
