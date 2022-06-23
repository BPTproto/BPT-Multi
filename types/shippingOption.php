<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one shipping option.
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


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
