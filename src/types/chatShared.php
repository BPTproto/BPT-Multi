<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about the chat whose identifier was shared with the bot using a
 * KeyboardButtonRequestChat button.
 */
class chatShared extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Identifier of the request */
    public int $request_id;

    /**
     * Identifier of the shared chat. This number may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a
     * 64-bit integer or double-precision float type are safe for storing this identifier. The bot may not have
     * access to the chat and could be unable to use this identifier, unless the chat is already known to the bot by
     * some other means.
     */
    public int $chat_id;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Get shared chat info by using getChat method
     *
     * @param bool|null $answer
     *
     * @return responseError|chat
     */
    public function getInfo (bool $answer = null): responseError|chat {
        return telegram::getChat($this->chat_id, answer: $answer);
    }
}
