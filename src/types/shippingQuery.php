<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about an incoming shipping query.
 */
class shippingQuery extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['from' => 'BPT\types\user', 'shipping_address' => 'BPT\types\shippingAddress'];

    /** Unique query identifier */
    public string $id;

    /** User who sent the query */
    public user $from;

    /** Bot specified invoice payload */
    public string $invoice_payload;

    /** User specified shipping address */
    public shippingAddress $shipping_address;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function answer (bool $ok, null|array $shipping_options = null, string|null $error_message = null): responseError|bool {
        return telegram::answerShippingQuery($ok, $this->id, $shipping_options, $error_message);
    }
}
