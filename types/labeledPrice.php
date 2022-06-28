<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a portion of the price for goods or services.
 * @method self setLabel(string $value)
 * @method self setAmount(int $value)
 */
class labeledPrice extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Portion label */
    public string $label;

    /**
     * Price of the product in the smallest units of the currency (integer, not float/double). For example, for a
     * price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits
     * past the decimal point for each currency (2 for the majority of currencies).
     */
    public int $amount;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
