<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the content of a venue message to be sent as the result of an inline query.
 */
class inputVenueMessageContent extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Latitude of the venue in degrees */
    public float $latitude;

    /** Longitude of the venue in degrees */
    public float $longitude;

    /** Name of the venue */
    public string $title;

    /** Address of the venue */
    public string $address;

    /** Optional. Foursquare identifier of the venue, if known */
    public string $foursquare_id;

    /**
     * Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public string $foursquare_type;

    /** Optional. Google Places identifier of the venue */
    public string $google_place_id;

    /** Optional. Google Places type of the venue. (See supported types.) */
    public string $google_place_type;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
