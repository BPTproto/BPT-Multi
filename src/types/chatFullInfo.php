<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains full information about a chat.
 */
class chatFullInfo extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'photo' => 'BPT\types\chatPhoto',
        'birthdate' => 'BPT\types\birthdate',
        'business_intro' => 'BPT\types\businessIntro',
        'business_location' => 'BPT\types\businessLocation',
        'business_opening_hours' => 'BPT\types\businessOpeningHours',
        'personal_chat' => 'BPT\types\chat',
        'array' => ['available_reactions' => 'BPT\types\reactionType'],
        'pinned_message' => 'BPT\types\message',
        'permissions' => 'BPT\types\chatPermissions',
        'location' => 'BPT\types\chatLocation',
    ];

    /**
     * Unique identifier for this chat. This number may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a
     * signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public null|int $id;

    /** Type of the chat, can be either “private”, “group”, “supergroup” or “channel” */
    public null|string $type;

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

    /**
     * Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link
     * preview. See accent colors for more details.
     */
    public null|int $accent_color_id;

    /** The maximum number of reactions that can be set on a message in the chat */
    public null|int $max_reaction_count;

    /** Optional. Chat photo */
    public null|chatPhoto $photo = null;

    /**
     * Optional. If non-empty, the list of all active chat usernames = null; for private chats, supergroups and channels
     * @var string[]
     */
    public null|array $active_usernames = null;

    /** Optional. For private chats, the date of birth of the user */
    public null|birthdate $birthdate = null;

    /** Optional. For private chats with business accounts, the intro of the business */
    public null|businessIntro $business_intro = null;

    /** Optional. For private chats with business accounts, the location of the business */
    public null|businessLocation $business_location = null;

    /** Optional. For private chats with business accounts, the opening hours of the business */
    public null|businessOpeningHours $business_opening_hours = null;

    /** Optional. For private chats, the personal channel of the user */
    public null|chat $personal_chat = null;

    /**
     * Optional. List of available reactions allowed in the chat. If omitted, then all emoji reactions are allowed.
     * @var reactionType[]
     */
    public null|array $available_reactions = null;

    /**
     * Optional. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview
     * background
     */
    public null|string $background_custom_emoji_id = null;

    /**
     * Optional. Identifier of the accent color for the chat's profile background. See profile accent colors for more
     * details.
     */
    public null|int $profile_accent_color_id = null;

    /** Optional. Custom emoji identifier of the emoji chosen by the chat for its profile background */
    public null|string $profile_background_custom_emoji_id = null;

    /** Optional. Custom emoji identifier of the emoji status of the chat or the other party in a private chat */
    public null|string $emoji_status_custom_emoji_id = null;

    /**
     * Optional. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time,
     * if any
     */
    public null|int $emoji_status_expiration_date = null;

    /** Optional. Bio of the other party in a private chat */
    public null|string $bio = null;

    /**
     * Optional. True, if privacy settings of the other party in the private chat allows to use
     * tg://user?id=<user_id> links only in chats with the user
     */
    public null|bool $has_private_forwards = null;

    /**
     * Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in
     * the private chat
     */
    public null|bool $has_restricted_voice_and_video_messages = null;

    /** Optional. True, if users need to join the supergroup before they can send messages */
    public null|bool $join_to_send_messages = null;

    /** Optional. True, if all users directly joining the supergroup need to be approved by supergroup administrators */
    public null|bool $join_by_request = null;

    /** Optional. Description, for groups, supergroups and channel chats */
    public null|string $description = null;

    /** Optional. Primary invite link, for groups, supergroups and channel chats */
    public null|string $invite_link = null;

    /** Optional. The most recent pinned message (by sending date) */
    public null|message $pinned_message = null;

    /** Optional. Default chat member permissions, for groups and supergroups */
    public null|chatPermissions $permissions = null;

    /**
     * Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged
     * user = null; in seconds
     */
    public null|int $slow_mode_delay = null;

    /**
     * Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to
     * ignore slow mode and chat permissions
     */
    public null|int $unrestrict_boost_count = null;

    /** Optional. The time after which all messages sent to the chat will be automatically deleted = null; in seconds */
    public null|int $message_auto_delete_time = null;

    /**
     * Optional. True, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to
     * chat administrators.
     */
    public null|bool $has_aggressive_anti_spam_enabled = null;

    /** Optional. True, if non-administrators can only get the list of bots and administrators in the chat */
    public null|bool $has_hidden_members = null;

    /** Optional. True, if messages from the chat can't be forwarded to other chats */
    public null|bool $has_protected_content = null;

    /** Optional. True, if new chat members will have access to old messages = null; available only to chat administrators */
    public null|bool $has_visible_history = null;

    /** Optional. For supergroups, name of the group sticker set */
    public null|string $sticker_set_name = null;

    /** Optional. True, if the bot can change the group sticker set */
    public null|bool $can_set_sticker_set = null;

    /**
     * Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be
     * used by all users and bots in the group.
     */
    public null|string $custom_emoji_sticker_set_name = null;

    /**
     * Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice
     * versa = null; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed
     * 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public null|int $linked_chat_id = null;

    /** Optional. For supergroups, the location to which the supergroup is connected */
    public null|chatLocation $location = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
