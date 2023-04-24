<?php

namespace BPT\types;

use stdClass;

/**
 * This object defines the criteria used to request a suitable user. The identifier of the selected user will be
 * shared with the bot when the corresponding button is pressed. More about requesting users »
 */
class keyboardButtonRequestUser extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /**
     * Signed 32-bit identifier of the request, which will be received back in the UserShared object. Must be unique
     * within the message
     */
    public int $request_id;

    /**
     * Optional. Pass True to request a bot, pass False to request a regular user. If not specified, no additional
     * restrictions are applied.
     */
    public bool $user_is_bot;

    /**
     * Optional. Pass True to request a premium user, pass False to request a non-premium user. If not specified, no
     * additional restrictions are applied.
     */
    public bool $user_is_premium;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
