<?php

namespace BPT\types;

use stdClass;

class businessOpeningHoursInterval extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /**
     * The minute's sequence number in a week, starting on Monday, marking the start of the time interval during
     * which the business is open; 0 - 7 * 24 * 60
     */
    public int $opening_minute;

    /**
     * The minute's sequence number in a week, starting on Monday, marking the end of the time interval during which
     * the business is open; 0 - 8 * 24 * 60
     */
    public int $closing_minute;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
