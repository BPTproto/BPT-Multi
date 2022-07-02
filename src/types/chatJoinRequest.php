<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a join request sent to a chat.
 */
class chatJoinRequest extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'chat' => 'BPT\types\chat',
        'from' => 'BPT\types\user',
        'invite_link' => 'BPT\types\chatInviteLink',
    ];

    /** Chat to which the request was sent */
    public chat $chat;

    /** User that sent the join request */
    public user $from;

    /** Date the request was sent in Unix time */
    public int $date;

    /** Optional. Bio of the user. */
    public string $bio;

    /** Optional. Chat invite link that was used by the user to send the join request */
    public chatInviteLink $invite_link;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
