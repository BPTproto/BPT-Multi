<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a venue.
 */
class venue extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['location' => 'BPT\types\location'];

    /** Venue location. Can't be a live location */
    public location $location;

    /** Name of the venue */
    public string $title;

    /** Address of the venue */
    public string $address;

    /** Optional. Foursquare identifier of the venue */
    public null|string $foursquare_id = null;

    /**
     * Optional. Foursquare type of the venue. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public null|string $foursquare_type = null;

    /** Optional. Google Places identifier of the venue */
    public null|string $google_place_id = null;

    /** Optional. Google Places type of the venue. (See supported types.) */
    public null|string $google_place_type = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
