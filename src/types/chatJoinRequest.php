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

    /**
     * Identifier of a private chat with the user who sent the join request. This number may have more than
     * 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it.
     * But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for
     * storing this identifier. The bot can use this identifier for 24 hours to send messages until the join request
     * is processed, assuming no other administrator contacted the user.
     */
    public int $user_chat_id;

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

    /**
     * Accept join request
     *
     * @param null|bool $answer
     *
     * @return responseError|bool
     */
    public function accept(bool $answer = null): responseError|bool {
        return telegram::approveChatJoinRequest($this->chat->id,$this->from->id, answer: $answer);
    }

    /**
     * Decline join request
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function deny(bool $answer = null): responseError|bool {
        return telegram::declineChatJoinRequest($this->chat->id,$this->from->id, answer: $answer);
    }

    /**
     * Revoke invite link
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function revokeLink(bool $answer = null): responseError|bool {
        return telegram::revokeChatInviteLink($this->invite_link->invite_link, $this->chat->id, answer: $answer);
    }
}
