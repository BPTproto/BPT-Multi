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
    public null|bool $is_anonymous = null;

    /** `creator` and `administrator` only. Custom title for this user */
    public null|string $custom_title = null;

    /** `administrator` only. True, if the bot is allowed to edit administrator privileges of that user */
    public null|bool $can_be_edited = null;

    /**
     * `administrator` only. True, if the administrator can access the chat event log, chat statistics, message statistics in
     * channels, see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by any other
     * administrator privilege
     */
    public null|bool $can_manage_chat = null;

    /** `administrator` only. True, if the administrator can delete messages of other users */
    public null|bool $can_delete_messages = null;

    /** `administrator` only. True, if the administrator can manage video chats */
    public null|bool $can_manage_video_chats = null;

    /** `administrator` only. True, if the administrator can restrict, ban or unban chat members */
    public null|bool $can_restrict_members = null;

    /**
     * `administrator` only. if the administrator can add new administrators with a subset of their own privileges or demote
     * administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by
     * the user)
     */
    public null|bool $can_promote_members = null;

    /** `administrator` and `restricted` only. True, if the user is allowed to change the chat title, photo and other settings */
    public null|bool $can_change_info = null;

    /** `administrator` and `restricted` only. True, if the user is allowed to invite new users to the chat */
    public null|bool $can_invite_users = null;

    /** `administrator` only. Optional. True, if the administrator can post in the channel; channels only */
    public null|bool $can_post_messages = null;

    /** `administrator` only. Optional. True, if the administrator can edit messages of other users and can pin messages; channels only */
    public null|bool $can_edit_messages = null;

    /** `administrator` and `restricted` only. Optional. True, if the user is allowed to pin messages; groups and supergroups only */
    public null|bool $can_pin_messages = null;
    /**
     * `administrator` : Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; supergroups only
     *
     * `restricted` : True, if the user is allowed to create forum topics
     */
    public null|bool $can_manage_topics = null;

    /** `restricted` only. True, if the user is a member of the chat at the moment of the request */
    public null|bool $is_member = null;

    /** `restricted` only. True, if the user is allowed to send text messages, contacts, locations and venues */
    public null|bool $can_send_messages = null;

    /** `restricted` only. True, if the user is allowed to send audios, documents, photos, videos, video notes and voice notes */
    public null|bool $can_send_media_messages = null;

    /** `restricted` only. True, if the user is allowed to send polls */
    public null|bool $can_send_polls = null;

    /** `restricted` only. True, if the user is allowed to send animations, games, stickers and use inline bots */
    public null|bool $can_send_other_messages = null;

    /** `restricted` only. True, if the user is allowed to add web page previews to their messages */
    public null|bool $can_add_web_page_previews = null;

    /** `kicked` and `restricted` only. Date when restrictions will be lifted for this user; unix time. If 0, then the user is restricted forever */
    public null|int $until_date = null;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}