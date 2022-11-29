<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains basic information about a successful payment.
 */
class successfulPayment extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['order_info' => 'BPT\types\orderInfo'];

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

    /** Telegram payment identifier */
    public string $telegram_payment_charge_id;

    /** Provider payment identifier */
    public string $provider_payment_charge_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
