<?php

namespace BPT\types;

use BPT\constants\chatType;
use BPT\telegram\telegram;
use CURLFile;
use stdClass;

/**
 * This object represents a chat.
 */
class chat extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'photo' => 'BPT\types\chatPhoto',
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

    /**
     * Optional. List of available reactions allowed in the chat. If omitted, then all emoji reactions are allowed.
     * Returned only in getChat.
     * @var reactionType[]
     */
    public null|array $available_reactions = null;

    /**
     * Optional. Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header,
     * and link preview. See accent colors for more details. Returned only in getChat. Always returned in getChat.
     */
    public null|int $accent_color_id = null;

    /**
     * Optional. Custom emoji identifier of emoji chosen by the chat for the reply header and link preview
     * background. Returned only in getChat.
     */
    public null|string $background_custom_emoji_id = null;

    /**
     * Optional. Identifier of the accent color for the chat's profile background. See profile accent colors for more
     * details. Returned only in getChat.
     */
    public null|int $profile_accent_color_id = null;

    /**
     * Optional. Custom emoji identifier of the emoji chosen by the chat for its profile background. Returned only in
     * getChat.
     */
    public null|string $profile_background_custom_emoji_id = null;

    /** Optional. Custom emoji identifier of emoji status of the other party in a private chat. Returned only in getChat. */
    public null|string $emoji_status_custom_emoji_id = null;

    /**
     * Optional. Expiration date of the emoji status of the other party in a private chat, if any.
     * Returned only in getChat.
     */
    public null|int $emoji_status_expiration_date;

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
     * Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to
     * ignore slow mode and chat permissions. Returned only in getChat.
     */
    public null|int $unrestrict_boost_count = null;

    /**
     * Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds.
     * Returned only in getChat.
     */
    public null|int $message_auto_delete_time = null;

    /**
     * Optional. True, if aggressive anti-spam checks are enabled in the supergroup.
     * The field is only available to chat administrators. Returned only in getChat.
     */
    public null|bool $has_aggressive_anti_spam_enabled = null;

    /**
     * Optional. True, if non-administrators can only get the list of bots and administrators in the chat.
     * Returned only in getChat.
     */
    public null|bool $has_hidden_members = null;

    /** Optional. True, if messages from the chat can't be forwarded to other chats. Returned only in getChat. */
    public null|bool $has_protected_content = null;

    /**
     * Optional. True, if new chat members will have access to old messages; available only to chat administrators.
     * Returned only in getChat.
     */
    public null|bool $has_visible_history = null;

    /** Optional. For supergroups, name of group sticker set. Returned only in getChat. */
    public null|string $sticker_set_name = null;

    /** Optional. True, if the bot can change the group sticker set. Returned only in getChat. */
    public null|bool $can_set_sticker_set = null;

    /**
     * Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be
     * used by all users and bots in the group. Returned only in getChat.
     */
    public null|string $custom_emoji_sticker_set_name = null;

    /**
     * Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice
     * versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed
     * 64-bit integer or double-precision float type are safe for storing this identifier. Returned only in getChat.
     */
    public null|int $linked_chat_id = null;

    /** Optional. For supergroups, the location to which the supergroup is connected. Returned only in getChat. */
    public null|chatLocation $location = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Is this chat is private or not
     *
     * @return bool
     */
    public function isPrivate (): bool {
        return $this->type === chatType::PRIVATE;
    }

    /**
     * Is this chat is normal group or not
     *
     * @return bool
     */
    public function isGroup (): bool {
        return $this->type === chatType::GROUP;
    }

    /**
     * Is this chat is suprtgroup or not
     *
     * @return bool
     */
    public function isSuperGroup (): bool {
        return $this->type === chatType::SUPERGROUP;
    }

    /**
     * Is this chat is channel or not
     *
     * @return bool
     */
    public function isChannel (): bool {
        return $this->type === chatType::CHANNEL;
    }

    /**
     * Leave this chat if it's not private
     *
     * @return responseError|bool
     */
    public function leave(): responseError|bool {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::leave($this->id);
    }

    /**
     * Set this chat photo if it's not private
     *
     * @param CURLFile|array $photo
     * @param null|bool      $answer
     *
     * @return responseError|bool
     */
    public function setPhoto(CURLFile|array $photo, bool $answer = null): responseError|bool {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::setChatPhoto($photo, $this->id, answer: $answer);
    }

    /**
     * Delete this chat photo if it's not private
     *
     * @param null|bool $answer
     *
     * @return responseError|bool
     */
    public function delPhoto(bool $answer = null): responseError|bool {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::deleteChatPhoto($this->id, answer: $answer);
    }

    /**
     * Set this chat title if it's not private
     *
     * @param string|array $title
     * @param bool|null    $answer
     *
     * @return responseError|bool
     */
    public function setTitle(string|array $title, bool $answer = null): responseError|bool {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::setChatTitle($title, $this->id, answer: $answer);
    }

    /**
     * Set this chat description if it's not private
     *
     * @param null|string $description
     * @param bool|null   $answer
     *
     * @return responseError|bool
     */
    public function setDescription(string|null $description = null, bool $answer = null): responseError|bool {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::setChatDescription($this->id, $description, answer: $answer);
    }

    /**
     * Get this chat admins if it's not private
     *
     * @param bool|null $answer
     *
     * @return bool|responseError|array
     */
    public function getAdmins(bool $answer = null): bool|responseError|array {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::getChatAdministrators($this->id, answer: $answer);
    }

    /**
     * Get this chat members count if it's not private
     *
     * @param bool|null $answer
     *
     * @return bool|responseError|int
     */
    public function getMembersCount(bool $answer = null): bool|responseError|int {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::getChatMemberCount($this->id, answer: $answer);
    }

    /**
     * Get member info in this chat if it's not private
     *
     * @param null|int  $user_id
     * @param bool|null $answer
     *
     * @return chatMember|bool|responseError
     */
    public function getMember(int|null $user_id = null, bool $answer = null): chatMember|bool|responseError {
        if ($this->isPrivate()) {
            return false;
        }
        return telegram::getChatMember($this->id, $user_id, answer: $answer);
    }
}
