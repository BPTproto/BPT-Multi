<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about the chat whose identifier was shared with the bot using a
 * KeyboardButtonRequestChat button.
 */
class chatShared extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['photo' => 'BPT\types\photoSize']];

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

    /** Optional. Title of the chat, if the title was requested by the bot. */
    public null|string $title = null;

    /** Optional. Username of the chat, if the username was requested by the bot and available. */
    public null|string $username = null;

    /**
     * Optional. Available sizes of the chat photo, if the photo was requested by the bot
     * @var photoSize[]
     */
    public null|array $photo = null;


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
