<?php

namespace BPT\types;

use stdClass;

class businessOpeningHours extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['opening_hours' => 'BPT\types\businessOpeningHoursInterval']];

    /** Unique name of the time zone for which the opening hours are defined */
    public string $time_zone_name;

    /**
     * List of time intervals describing business opening hours
     * @var businessOpeningHoursInterval[]
     */
    public array $opening_hours;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
