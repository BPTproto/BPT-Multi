<?php

namespace BPT\types;

use stdClass;

/**
 * Describes the connection of the bot with a business account.
 */
class businessConnection extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** Unique identifier of the business connection */
    public string $id;

    /** Business account user that created the business connection */
    public user $user;

    /**
     * Identifier of a private chat with the user who created the business connection. This number may have more than
     * 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But
     * it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing
     * this identifier.
     */
    public int $user_chat_id;

    /** Date the connection was established in Unix time */
    public int $date;

    /** True, if the bot can act on behalf of the business account in chats that were active in the last 24 hours */
    public bool $can_reply;

    /** True, if the connection is active */
    public bool $is_enabled;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
