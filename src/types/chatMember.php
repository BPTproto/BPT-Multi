<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about one member of a chat
 */
class chatMember extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** The member's status in the chat, could be `creator`, `administrator`, `member`, `restricted`, `left`, `kicked` */
    public string $status;

    /** Information about the user */
    public user $user;

    /** `creator` and `administrator` only. True, if the user's presence in the chat is hidden */
    public bool $is_anonymous;

    /** `creator` and `administrator` only. Custom title for this user */
    public string $custom_title;

    /** `administrator` only. True, if the bot is allowed to edit administrator privileges of that user */
    public bool $can_be_edited;

    /**
     * `administrator` only. True, if the administrator can access the chat event log, chat statistics, message statistics in
     * channels, see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by any other
     * administrator privilege
     */
    public bool $can_manage_chat;

    /** `administrator` only. True, if the administrator can delete messages of other users */
    public bool $can_delete_messages;

    /** `administrator` only. True, if the administrator can manage video chats */
    public bool $can_manage_video_chats;

    /** `administrator` only. True, if the administrator can restrict, ban or unban chat members */
    public bool $can_restrict_members;

    /**
     * `administrator` only. if the administrator can add new administrators with a subset of their own privileges or demote
     * administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by
     * the user)
     */
    public bool $can_promote_members;

    /** `administrator` and `restricted` only. True, if the user is allowed to change the chat title, photo and other settings */
    public bool $can_change_info;

    /** `administrator` and `restricted` only. True, if the user is allowed to invite new users to the chat */
    public bool $can_invite_users;

    /** `administrator` only. Optional. True, if the administrator can post in the channel; channels only */
    public bool $can_post_messages;

    /** `administrator` only. Optional. True, if the administrator can edit messages of other users and can pin messages; channels only */
    public bool $can_edit_messages;

    /** `administrator` and `restricted` only. Optional. True, if the user is allowed to pin messages; groups and supergroups only */
    public bool $can_pin_messages;
    /**
     * `administrator` : Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; supergroups only
     *
     * `restricted` : True, if the user is allowed to create forum topics
     */
    public bool $can_manage_topics;

    /** `restricted` only. True, if the user is a member of the chat at the moment of the request */
    public bool $is_member;

    /** `restricted` only. True, if the user is allowed to send text messages, contacts, locations and venues */
    public bool $can_send_messages;

    /** `restricted` only. True, if the user is allowed to send audios, documents, photos, videos, video notes and voice notes */
    public bool $can_send_media_messages;

    /** `restricted` only. True, if the user is allowed to send polls */
    public bool $can_send_polls;

    /** `restricted` only. True, if the user is allowed to send animations, games, stickers and use inline bots */
    public bool $can_send_other_messages;

    /** `restricted` only. True, if the user is allowed to add web page previews to their messages */
    public bool $can_add_web_page_previews;

    /** `kicked` and `restricted` only. Date when restrictions will be lifted for this user; unix time. If 0, then the user is restricted forever */
    public int $until_date;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}