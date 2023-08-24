<?php

namespace BPT\types;

use BPT\constants\parseMode;
use BPT\telegram\telegram;
use BPT\tools\tools;
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

    /**
     * Get user invite link(referral link)
     *
     * Same as tools::inviteLink($user_id);
     *
     * These links can be proceeded by library databases
     *
     * @return string
     */
    public function inviteLink(): string {
        return tools::inviteLink($this->id);
    }

    /**
     * Get fullname of user
     *
     * If last name exist : Firstname . ' ' . lastname
     *
     * if not : Firstname
     *
     * @param bool $nameFirst
     *
     * @return string
     */
    public function fullName(bool $nameFirst = true): string {
        return trim($nameFirst ? $this->first_name . ' ' . $this->last_name : $this->last_name . ' ' . $this->first_name);
    }

    /**
     * Get user profiles
     *
     * @param null|int  $offset
     * @param null|int  $limit
     * @param null|bool $answer
     *
     * @return userProfilePhotos|responseError
     */
    public function getProfiles(int|null $offset = null, int|null $limit = null, bool $answer = null): userProfilePhotos|responseError {
        return telegram::getUserProfilePhotos($this->id, $offset, $limit, answer: $answer);
    }

    /**
     * Get user mention link for different parse mode
     *
     * if link_text parameter is empty, it will use fullname for link text
     *
     * @param string $link_text
     * @param string $parse_mode
     *
     * @return string
     */
    public function getMention (string $link_text = '', string $parse_mode = '') {
        if (empty($link_text)) {
            $link_text = $this->fullName();
        }

        if ($parse_mode === parseMode::HTML) {
            return "<a href=\"tg://user?id=$this->id\">$link_text</a>";
        }

        if ($parse_mode === parseMode::MARKDOWN || $parse_mode === parseMode::MARKDOWNV2) {
            return "[$link_text](tg://user?id=$this->id)";
        }

        return "tg://user?id=$this->id";
    }
}
