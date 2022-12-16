<?php

namespace BPT\types;

use BPT\telegram\telegram;
use BPT\tools;
use stdClass;

/**
 * This object represents a Telegram user or bot.
 */
class user extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Unique identifier for this user or bot. This number may have more than 32 significant bits and some
     * programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant
     * bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public int $id;

    /** True, if this user is a bot */
    public null|bool $is_bot = null;

    /** User's or bot's first name */
    public null|string $first_name = null;

    /** Optional. User's or bot's last name */
    public null|string $last_name = null;

    /** Optional. User's or bot's username */
    public null|string $username = null;

    /** Optional. IETF language tag of the user's language */
    public null|string $language_code = null;

    /** Optional. True, if this user is a Telegram Premium user */
    public null|bool $is_premium = null;

    /** Optional. True, if this user added the bot to the attachment menu */
    public null|bool $added_to_attachment_menu = null;

    /** Optional. True, if the bot can be invited to groups. Returned only in getMe. */
    public null|bool $can_join_groups = null;

    /** Optional. True, if privacy mode is disabled for the bot. Returned only in getMe. */
    public null|bool $can_read_all_group_messages = null;

    /** Optional. True, if the bot supports inline queries. Returned only in getMe. */
    public null|bool $supports_inline_queries = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function inviteLink(): string {
        return tools::inviteLink($this->id);
    }

    public function fullName(bool $nameFirst = true): string {
        return trim($nameFirst ? $this->first_name . ' ' . $this->last_name : $this->last_name . ' ' . $this->first_name);
    }

    public function getProfiles(int|null $offset = null, int|null $limit = null): userProfilePhotos|responseError {
        return telegram::getUserProfilePhotos($this->id,$offset,$limit);
    }
}
