<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a list of boosts added to a chat by a user.
 */
class userChatBoosts extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['boosts' => 'BPT\types\chatBoost']];

    /**
     * The list of boosts added to the chat by the user
     * @var chatBoost[]
     */
    public array $boosts;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
