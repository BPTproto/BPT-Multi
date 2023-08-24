<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about the user whose identifier was shared with the bot using a
 * KeyboardButtonRequestUser button.
 */
class userShared extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Identifier of the request */
    public int $request_id;

    /**
     * Identifier of the shared user. This number may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a
     * 64-bit integer or double-precision float type are safe for storing this identifier. The bot may not have
     * access to the user and could be unable to use this identifier, unless the user is already known to the bot by
     * some other means.
     */
    public int $user_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Get shared user info by using getChat method
     *
     * @param bool|null $answer
     *
     * @return responseError|chat
     */
    public function getInfo (bool $answer = null): responseError|chat {
        return telegram::getChat($this->user_id, answer: $answer);
    }
}
