<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents type of a poll, which is allowed to be created and sent when the corresponding button
 * is pressed.
 * @method self setType(string $value)
 */
class keyboardButtonPollType extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Optional. If quiz is passed, the user will be allowed to create only polls in the quiz mode. If regular is
     * passed, only regular polls will be allowed. Otherwise, the user will be allowed to create a poll of any type.
     */
    public string $type;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
