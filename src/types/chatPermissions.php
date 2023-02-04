<?php

namespace BPT\types;

use stdClass;

/**
 * Describes actions that a non-administrator user is allowed to take in a chat.
 * @method self setCan_send_messages(bool $value)
 * @method self setCan_send_audios(bool $value)
 * @method self setCan_send_documents(bool $value)
 * @method self setCan_send_photos(bool $value)
 * @method self setCan_send_videos(bool $value)
 * @method self setCan_send_video_notes(bool $value)
 * @method self setCan_send_voice_notes(bool $value)
 * @method self setCan_send_polls(bool $value)
 * @method self setCan_send_other_messages(bool $value)
 * @method self setCan_add_web_page_previews(bool $value)
 * @method self setCan_change_info(bool $value)
 * @method self setCan_invite_users(bool $value)
 * @method self setCan_pin_messages(bool $value)
 * @method self setCan_manage_topics(bool $value)
 */
class chatPermissions extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Optional. True, if the user is allowed to send text messages, contacts, invoices, locations and venues */
    public bool $can_send_messages;

    /** Optional. True, if the user is allowed to send audios */
    public bool $can_send_audios;

    /** Optional. True, if the user is allowed to send documents */
    public bool $can_send_documents;

    /** Optional. True, if the user is allowed to send photos */
    public bool $can_send_photos;

    /** Optional. True, if the user is allowed to send videos */
    public bool $can_send_videos;

    /** Optional. True, if the user is allowed to send video notes */
    public bool $can_send_video_notes;

    /** Optional. True, if the user is allowed to send voice notes */
    public bool $can_send_voice_notes;

    /** Optional. True, if the user is allowed to send polls */
    public bool $can_send_polls;

    /** Optional. True, if the user is allowed to send animations, games, stickers and use inline bots */
    public bool $can_send_other_messages;

    /** Optional. True, if the user is allowed to add web page previews to their messages */
    public bool $can_add_web_page_previews;

    /**
     * Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public
     * supergroups
     */
    public bool $can_change_info;

    /** Optional. True, if the user is allowed to invite new users to the chat */
    public bool $can_invite_users;

    /** Optional. True, if the user is allowed to pin messages. Ignored in public supergroups */
    public bool $can_pin_messages;

    /**
     * Optional. True, if the user is allowed to create forum topics. If omitted defaults to the value of
     * can_pin_messages
     */
    public bool $can_manage_topics;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
