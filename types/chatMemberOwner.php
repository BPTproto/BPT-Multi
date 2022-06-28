<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a chat member that owns the chat and has all administrator privileges.
 */
class chatMemberOwner extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** The member's status in the chat, always “creator” */
    public string $status;

    /** Information about the user */
    public user $user;

    /** True, if the user's presence in the chat is hidden */
    public bool $is_anonymous;

    /** Optional. Custom title for this user */
    public string $custom_title;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
