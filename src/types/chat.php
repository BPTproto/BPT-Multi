<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a chat.
 */
class chat extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'photo' => 'BPT\types\chatPhoto',
        'pinned_message' => 'BPT\types\message',
        'permissions' => 'BPT\types\chatPermissions',
        'location' => 'BPT\types\chatLocation',
    ];

    /**
     * Unique identifier for this chat. This number may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a
     * signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public int $id;

    /** Type of chat, can be either “private”, “group”, “supergroup” or “channel” */
    public string $type;

    /** Optional. Title, for supergroups, channels and group chats */
    public null|string $title = null;

    /** Optional. Username, for private chats, supergroups and channels if available */
    public null|string $username = null;

    /** Optional. First name of the other party in a private chat */
    public null|string $first_name = null;

    /** Optional. Last name of the other party in a private chat */
    public null|string $last_name = null;

    /** Optional. True, if the supergroup chat is a forum (has topics enabled) */
    public null|bool $is_forum = null;

    /** Optional. Chat photo. Returned only in getChat. */
    public null|chatPhoto $photo = null;

    /** Optional. If non-empty, the list of all active chat usernames; for private chats, supergroups and channels. Returned only in getChat. */
    public null|array $active_usernames = null;

    /** Optional. Custom emoji identifier of emoji status of the other party in a private chat. Returned only in getChat. */
    public null|string $emoji_status_custom_emoji_id = null;

    /** Optional. Bio of the other party in a private chat. Returned only in getChat. */
    public null|string $bio = null;

    /**
     * Optional. True, if privacy settings of the other party in the private chat allows to use
     * tg://user?id=<user_id> links only in chats with the user. Returned only in getChat.
     */
    public null|bool $has_private_forwards = null;

    /**
     * Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in
     * the private chat. Returned only in getChat.
     */
    public null|bool $has_restricted_voice_and_video_messages = null;

    /** Optional. True, if users need to join the supergroup before they can send messages. Returned only in getChat. */
    public null|bool $join_to_send_messages = null;

    /**
     * Optional. True, if all users directly joining the supergroup need to be approved by supergroup administrators.
     * Returned only in getChat.
     */
    public null|bool $join_by_request = null;

    /** Optional. Description, for groups, supergroups and channel chats. Returned only in getChat. */
    public null|string $description = null;

    /** Optional. Primary invite link, for groups, supergroups and channel chats. Returned only in getChat. */
    public null|string $invite_link = null;

    /** Optional. The most recent pinned message (by sending date). Returned only in getChat. */
    public null|message $pinned_message = null;

    /** Optional. Default chat member permissions, for groups and supergroups. Returned only in getChat. */
    public null|chatPermissions $permissions = null;

    /**
     * Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unpriviledged
     * user; in seconds. Returned only in getChat.
     */
    public null|int $slow_mode_delay = null;

    /**
     * Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds.
     * Returned only in getChat.
     */
    public null|int $message_auto_delete_time = null;

    /** Optional. True, if messages from the chat can't be forwarded to other chats. Returned only in getChat. */
    public null|bool $has_protected_content = null;

    /** Optional. For supergroups, name of group sticker set. Returned only in getChat. */
    public null|string $sticker_set_name = null;

    /** Optional. True, if the bot can change the group sticker set. Returned only in getChat. */
    public null|bool $can_set_sticker_set = null;

    /**
     * Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice
     * versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed
     * 64 bit integer or double-precision float type are safe for storing this identifier. Returned only in getChat.
     */
    public null|int $linked_chat_id = null;

    /** Optional. For supergroups, the location to which the supergroup is connected. Returned only in getChat. */
    public null|chatLocation $location = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
