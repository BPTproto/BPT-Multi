<?php

namespace BPT\types;

use BPT\constants\chatMemberStatus;
use BPT\settings;
use stdClass;

/**
 * This object represents changes in the status of a chat member.
 */
class chatMemberUpdated extends types {
    /** Keep all properties which has sub properties */
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

    /**
     * Optional. True, if the user joined the chat after sending a direct join request and being approved by an
     * administrator
     */
    public null|bool $via_join_request = null;

    /** Optional. True, if the user joined the chat via a chat folder invite link */
    public null|bool $via_chat_folder_invite_link = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Check if user unblocked me(started chat again)
     *
     * @return bool
     */
    public function isUnBlockedMe(): bool {
        return $this->chat->isPrivate() && $this->isMe() && $this->isJoined();
    }

    /**
     * Check if user blocked me(stopped chat)
     *
     * @return bool
     */
    public function isBlockedMe(): bool {
        return $this->chat->isPrivate() && $this->isMe() && $this->isKicked();
    }

    /**
     * Check is it related to me(e.g: adding bot to a group)
     *
     * @return bool
     */
    public function isMe (): bool {
        return $this->new_chat_member->user->id == settings::$bot_id;
    }

    /**
     * Check is it an add member update(e.g: someone added something to group)
     *
     * @return bool
     */
    public function isAdded(): bool {
        return $this->isJoined() && $this->from->id !== $this->new_chat_member->user->id;
    }

    /**
     * Check is it join update(e.g: someone joined in group)
     *
     * @return bool
     */
    public function isJoined(): bool {
        return $this->new_chat_member->status === chatMemberStatus::MEMBER && !$this->isOldAdmin();
    }

    /**
     * Check is it joined by link update(e.g: someone joined by link in group)
     *
     * @return bool
     */
    public function isJoinedByLink(): bool {
        return $this->isJoined() && !empty($this->invite_link);
    }

    /**
     * Check if it's a leave update(e.g: someone leaved group)
     *
     * @return bool
     */
    public function isLeaved (): bool {
        return $this->new_chat_member->status === chatMemberStatus::LEFT;
    }

    /**
     * Check if it's a kick update(e.g: someone kicked from group by admin)
     *
     * @return bool
     */
    public function isKicked (): bool {
        return $this->new_chat_member->status === chatMemberStatus::KICKED;
    }

    /**
     * Check if it's an old admin update(e.g: an admin is demoted)
     *
     * @return bool
     */
    public function isOldAdmin (): bool {
        return $this->old_chat_member->status === chatMemberStatus::ADMINISTRATOR;
    }

    /**
     * Check if it's a new admin update(e.g: someone promoted to admin)
     *
     * @return bool
     */
    public function isNewAdmin (): bool {
        return $this->new_chat_member->status === chatMemberStatus::ADMINISTRATOR;
    }
}
