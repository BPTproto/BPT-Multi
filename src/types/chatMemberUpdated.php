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
    public chatInviteLink $invite_link;

    /** Optional. True, if the user joined the chat via a chat folder invite link */
    public bool $via_chat_folder_invite_link;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function isUnBlockedMe(): bool {
        return $this->chat->isPrivate() && $this->isMe() && $this->isJoined();
    }

    public function isBlockedMe(): bool {
        return $this->chat->isPrivate() && $this->isMe() && $this->isKicked();
    }

    public function isMe (): bool {
        return $this->new_chat_member->user->id == settings::$bot_id;
    }

    public function isAdded(): bool {
        return $this->isJoined() && $this->from->id !== $this->new_chat_member->user->id;
    }

    public function isJoined(): bool {
        return $this->new_chat_member->status === chatMemberStatus::MEMBER;
    }

    public function isJoinedByLink(): bool {
        return $this->isJoined() && !empty($this->invite_link);
    }

    public function isLeaved (): bool {
        return $this->new_chat_member->status === chatMemberStatus::LEFT;
    }

    public function isKicked (): bool {
        return $this->new_chat_member->status === chatMemberStatus::KICKED;
    }

    public function isOldAdmin (): bool {
        return $this->old_chat_member->status === chatMemberStatus::ADMINISTRATOR && $this->isJoined();
    }

    public function isNewAdmin (): bool {
        return $this->new_chat_member->status === chatMemberStatus::ADMINISTRATOR;
    }
}
