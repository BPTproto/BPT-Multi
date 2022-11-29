<?php

namespace BPT\types;

use stdClass;

/**
 * Describes actions that a non-administrator user is allowed to take in a chat.
 * @method self setCan_send_messages(bool $value)
 * @method self setCan_send_media_messages(bool $value)
 * @method self setCan_send_polls(bool $value)
 * @method self setCan_send_other_messages(bool $value)
 * @method self setCan_add_web_page_previews(bool $value)
 * @method self setCan_change_info(bool $value)
 * @method self setCan_invite_users(bool $value)
 * @method self setCan_pin_messages(bool $value)
 */
class chatPermissions extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Optional. True, if the user is allowed to send text messages, contacts, locations and venues */
    public null|bool $can_send_messages = null;

    /**
     * Optional. True, if the user is allowed to send audios, documents, photos, videos, video notes and voice notes,
     * implies can_send_messages
     */
    public null|bool $can_send_media_messages = null;

    /** Optional. True, if the user is allowed to send polls, implies can_send_messages */
    public null|bool $can_send_polls = null;

    /**
     * Optional. True, if the user is allowed to send animations, games, stickers and use inline bots, implies
     * can_send_media_messages
     */
    public null|bool $can_send_other_messages = null;

    /**
     * Optional. True, if the user is allowed to add web page previews to their messages, implies
     * can_send_media_messages
     */
    public null|bool $can_add_web_page_previews = null;

    /**
     * Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public
     * supergroups
     */
    public null|bool $can_change_info = null;

    /** Optional. True, if the user is allowed to invite new users to the chat */
    public null|bool $can_invite_users = null;

    /** Optional. True, if the user is allowed to pin messages. Ignored in public supergroups */
    public null|bool $can_pin_messages = null;

    /** Optional. True, if the user is allowed to create forum topics. If omitted defaults to the value of can_pin_messages */
    public null|bool $can_manage_topics = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
