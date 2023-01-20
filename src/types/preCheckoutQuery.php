<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about an incoming pre-checkout query.
 */
class preCheckoutQuery extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['from' => 'BPT\types\user', 'order_info' => 'BPT\types\orderInfo'];

    /** Unique query identifier */
    public string $id;

    /** User who sent the query */
    public user $from;

    /** Three-letter ISO 4217 currency code */
    public string $currency;

    /**
     * Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$
     * 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the
     * decimal point for each currency (2 for the majority of currencies).
     */
    public int $total_amount;

    /** Bot specified invoice payload */
    public string $invoice_payload;

    /** Optional. Identifier of the shipping option chosen by the user */
    public null|string $shipping_option_id = null;

    /** Optional. Order information provided by the user */
    public null|orderInfo $order_info = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function answer (bool $ok, string|null $error_message = null): responseError|bool {
        return telegram::answerPreCheckoutQuery($ok, $this->id, $error_message);
    }
}
