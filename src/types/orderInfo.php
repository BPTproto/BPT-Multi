<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents information about an order.
 */
class orderInfo extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['shipping_address' => 'BPT\types\shippingAddress'];

    /** Optional. User name */
    public null|string $name = null;

    /** Optional. User's phone number */
    public null|string $phone_number = null;

    /** Optional. User email */
    public null|string $email = null;

    /** Optional. User shipping address */
    public null|shippingAddress $shipping_address = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
