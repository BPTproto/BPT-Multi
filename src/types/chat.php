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
    private const subs = [];

    /**
     * Unique identifier for this chat. This number may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a
     * signed 64-bit integer or double-precision float type are safe for storing this identifier.
     */
    public int $id;

    /** Type of the chat, can be either “private”, “group”, “supergroup” or “channel” */
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
