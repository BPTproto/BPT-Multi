<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * Represents an invite link for a chat.
 */
class chatInviteLink extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['creator' => 'BPT\types\user'];

    /**
     * The invite link. If the link was created by another chat administrator, then the second part of the link will
     * be replaced with “…”.
     */
    public string $invite_link;

    /** Creator of the link */
    public user $creator;

    /** True, if users joining the chat via the link need to be approved by chat administrators */
    public bool $creates_join_request;

    /** True, if the link is primary */
    public bool $is_primary;

    /** True, if the link is revoked */
    public bool $is_revoked;

    /** Optional. Invite link name */
    public null|string $name = null;

    /** Optional. Point in time (Unix timestamp) when the link will expire or has been expired */
    public null|int $expire_date = null;

    /**
     * Optional. The maximum number of users that can be members of the chat simultaneously after joining the chat
     * via this invite link; 1-99999
     */
    public null|int $member_limit = null;

    /** Optional. Number of pending join requests created using this link */
    public null|int $pending_join_request_count = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * revoke the link if not already revoked
     *
     * @return responseError|bool|self
     */
    public function revoke(): self|responseError|bool {
        return $this->is_revoked ?? telegram::revokeChatInviteLink($this->invite_link);
    }
}
