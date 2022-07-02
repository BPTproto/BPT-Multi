<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one shipping option.
 * @method self setId(string $value)
 * @method self setTitle(string $value)
 * @method self setPrices(array $value)
 */
class shippingOption extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['prices' => 'BPT\types\labeledPrice']];

    /** Shipping option identifier */
    public string $id;

    /** Option title */
    public string $title;

    /**
     * List of price portions
     * @var labeledPrice[]
     */
    public array $prices;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
