<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object contains information about the users whose identifiers were shared with the bot using a
 * KeyboardButtonRequestUsers button.
 */
class usersShared extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Identifier of the request */
    public int $request_id;

    /**
     * Identifiers of the shared users. These numbers may have more than 32 significant bits and some programming
     * languages may have difficulty/silent defects in interpreting them. But they have at most 52 significant bits,
     * so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not
     * have access to the users and could be unable to use these identifiers, unless the users are already known to
     * the bot by some other means.
     * @var int[]
     */
    public array $user_ids;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * Get shared user info by using getChat method
     *
     * @param bool|null $answer
     */
    public function getInfo (bool $answer = null): array {
        $result = [];
        foreach ($this->user_ids as $user_id) {
            $result[$user_id] = telegram::getChat($user_id, answer: $answer);
        }
        return $result;
    }
}
