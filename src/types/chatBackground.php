<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a chat background.
 *
 * @method self setBackgroundType(backgroundType $value)
 */
class chatBackground extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['type' => 'BPT\types\backgroundType'];

    /** Type of the background */
    public backgroundType $type;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
