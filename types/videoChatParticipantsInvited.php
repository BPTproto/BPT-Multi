<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about new members invited to a video chat.
 */
class videoChatParticipantsInvited extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['users' => 'BPT\types\user']];

    /**
     * New members that were invited to the video chat
     * @var user[]
     */
    public array $users;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
