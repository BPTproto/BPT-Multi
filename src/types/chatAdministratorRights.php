<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the rights of an administrator in a chat.
 * @method self setIs_anonymous(bool $value)
 * @method self setCan_manage_chat(bool $value)
 * @method self setCan_delete_messages(bool $value)
 * @method self setCan_manage_video_chats(bool $value)
 * @method self setCan_restrict_members(bool $value)
 * @method self setCan_promote_members(bool $value)
 * @method self setCan_change_info(bool $value)
 * @method self setCan_invite_users(bool $value)
 * @method self setCan_post_stories(bool $value)
 * @method self setCan_edit_stories(bool $value)
 * @method self setCan_delete_stories(bool $value)
 * @method self setCan_post_messages(bool $value)
 * @method self setCan_edit_messages(bool $value)
 * @method self setCan_pin_messages(bool $value)
 * @method self setCan_manage_topics(bool $value)
 */
class chatAdministratorRights extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** True, if the user's presence in the chat is hidden */
    public bool $is_anonymous;

    /**
     * True, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel
     * members, report spam messages and ignore slow mode. Implied by any other administrator privilege.
     */
    public bool $can_manage_chat;

    /** True, if the administrator can delete messages of other users */
    public bool $can_delete_messages;

    /** True, if the administrator can manage video chats */
    public bool $can_manage_video_chats;

    /** True, if the administrator can restrict, ban or unban chat members, or access supergroup statistics */
    public bool $can_restrict_members;

    /**
     * True, if the administrator can add new administrators with a subset of their own privileges or demote
     * administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed
     * by the user)
     */
    public bool $can_promote_members;

    /** True, if the user is allowed to change the chat title, photo and other settings */
    public bool $can_change_info;

    /** True, if the user is allowed to invite new users to the chat */
    public bool $can_invite_users;

    /** True, if the administrator can post stories to the chat */
    public bool $can_post_stories;

    /**
     * True, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat
     * stories, and access the chat's story archive
     */
    public bool $can_edit_stories;

    /** True, if the administrator can delete stories posted by other users */
    public bool $can_delete_stories;

    /**
     * Optional. True, if the administrator can post messages in the channel, or access channel statistics; for
     * channels only
     */
    public bool $can_post_messages;

    /** Optional. True, if the administrator can edit messages of other users and can pin messages; for channels only */
    public bool $can_edit_messages;

    /** Optional. True, if the user is allowed to pin messages; for groups and supergroups only */
    public bool $can_pin_messages;

    /** Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only */
    public bool $can_manage_topics;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
