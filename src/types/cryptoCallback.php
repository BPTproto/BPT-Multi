<?php

namespace BPT\types;

use stdClass;

class cryptoCallback extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    public string $status;

    /** @var int Order id in your database */
    public int $order_id;

    /** @var int The related user */
    public int $user_id;

    /** @var string Description of order */
    public string $description;

    /** @var int|float Real amount or known as requested amount */
    public int|float $real_amount;

    /** @var string Fiat currency(aka usd, euro, ...) */
    public string $currency;

    /** @var int|float Paid amount in this payment */
    public int|float $paid_amount;

    /** @var int|float Total paid amount for the order id */
    public int|float $total_paid;

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
