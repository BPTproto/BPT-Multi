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
    public string $name;

    /** Optional. User's phone number */
    public string $phone_number;

    /** Optional. User email */
    public string $email;

    /** Optional. User shipping address */
    public shippingAddress $shipping_address;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
