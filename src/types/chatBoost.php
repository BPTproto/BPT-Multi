<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about a chat boost.
 */
class chatBoost extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['source' => 'BPT\types\chatBoostSource'];

    /** Unique identifier of the boost */
    public string $boost_id;

    /** Point in time (Unix timestamp) when the chat was boosted */
    public int $add_date;

    /**
     * Point in time (Unix timestamp) when the boost will automatically expire, unless the booster's Telegram Premium
     * subscription is prolonged
     */
    public int $expiration_date;

    /** Source of the added boost */
    public chatBoostSource $source;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
