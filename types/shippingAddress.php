<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a shipping address.
 */
class shippingAddress extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Two-letter ISO 3166-1 alpha-2 country code */
    public string $country_code;

    /** State, if applicable */
    public string $state;

    /** City */
    public string $city;

    /** First line for the address */
    public string $street_line1;

    /** Second line for the address */
    public string $street_line2;

    /** Address post code */
    public string $post_code;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
