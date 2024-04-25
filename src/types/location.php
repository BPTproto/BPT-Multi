<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a point on the map.
 */
class location extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Latitude as defined by sender */
    public float $latitude;

    /** Longitude as defined by sender */
    public float $longitude;

    /** Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
    public null|float $horizontal_accuracy = null;

    /**
     * Optional. Time relative to the message sending date, during which the location can be updated; in seconds. For
     * active live locations only.
     */
    public null|int $live_period = null;

    /** Optional. The direction in which user is moving, in degrees; 1-360. For active live locations only. */
    public null|int $heading = null;

    /**
     * Optional. The maximum distance for proximity alerts about approaching another chat member, in meters. For sent
     * live locations only.
     */
    public null|int $proximity_alert_radius = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Get google map link for this location
     *
     * @return string
     */
    public function mapLink (): string {
        return "https://www.google.com/maps/search/$this->latitude,$this->longitude";
    }
}
