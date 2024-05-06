<?php

namespace BPT\types;

use stdClass;

/**
 * Describes the birthdate of a user.
 */
class birthdate extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Day of the user's birth; 1-31 */
    public int $day;

    /** Month of the user's birth; 1-12 */
    public int $month;

    /** Optional. Year of the user's birth */
    public null|int $year = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
