<?php

namespace BPT\types;

use BPT\telegram\telegram;
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
    public null|string $bio = null;

    /** Optional. Chat invite link that was used by the user to send the join request */
    public null|chatInviteLink $invite_link = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function accept(): responseError|bool {
        return telegram::approveChatJoinRequest($this->chat->id,$this->from->id);
    }

    public function deny(): responseError|bool {
        return telegram::declineChatJoinRequest($this->chat->id,$this->from->id);
    }
}
