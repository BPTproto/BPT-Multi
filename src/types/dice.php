<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an animated emoji that displays a random value.
 */
class dice extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Emoji on which the dice throw animation is based */
    public string $emoji;

    /**
     * Value of the dice, 1-6 for “🎲”, “🎯” and “🎳” base emoji, 1-5 for “🏀” and “⚽”
     * base emoji, 1-64 for “🎰” base emoji
     */
    public int $value;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
