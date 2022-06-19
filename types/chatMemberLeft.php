<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a chat member that isn't currently a member of the chat, but may join it themselves.
 */
class chatMemberLeft extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** The member's status in the chat, always “left” */
    public string $status;

    /** Information about the user */
    public user $user;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
