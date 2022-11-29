<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents changes in the status of a chat member.
 */
class chatMemberUpdated extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'chat' => 'BPT\types\chat',
        'from' => 'BPT\types\user',
        'old_chat_member' => 'BPT\types\chatMember',
        'new_chat_member' => 'BPT\types\chatMember',
        'invite_link' => 'BPT\types\chatInviteLink',
    ];

    /** Chat the user belongs to */
    public chat $chat;

    /** Performer of the action, which resulted in the change */
    public user $from;

    /** Date the change was done in Unix time */
    public int $date;

    /** Previous information about the chat member */
    public chatMember $old_chat_member;

    /** New information about the chat member */
    public chatMember $new_chat_member;

    /**
     * Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events
     * only.
     */
    public null|chatInviteLink $invite_link = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
