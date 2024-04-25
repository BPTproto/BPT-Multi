<?php

namespace BPT\types;

use stdClass;

/**
 * This object defines the criteria used to request suitable users. The identifiers of the selected users will be
 * shared with the bot when the corresponding button is pressed. More about requesting users »
 *
 * @method self setRequest_id(int $value)
 * @method self setUser_is_bot(bool $value)
 * @method self setUser_is_premium(bool $value)
 * @method self setMax_quantity(int $value)
 *
 * @deprecated use keyboardButtonRequestUsers instead
 */
class keyboardButtonRequestUser extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /**
     * Signed 32-bit identifier of the request that will be received back in the UsersShared object. Must be unique
     * within the message
     */
    public int $request_id;

    /**
     * Optional. Pass True to request bots, pass False to request regular users. If not specified, no additional
     * restrictions are applied.
     */
    public bool $user_is_bot;

    /**
     * Optional. Pass True to request premium users, pass False to request non-premium users. If not specified, no
     * additional restrictions are applied.
     */
    public bool $user_is_premium;

    /** Optional. The maximum number of users to be selected; 1-10. Defaults to 1. */
    public int $max_quantity;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
