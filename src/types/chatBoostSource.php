<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the source of a chat boost.
 */
class chatBoostSource extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /** Could be "premium", "gift_code" or "giveaway" */
    public string $source;

    /**
     * User(premium) that boosted the chat,
     * Or User(gift_code) for which the gift code was created
     * Or User(giveaway) that won the prize in the giveaway if any
     */
    public null|user $user = null;

    /**
     * `giveaway` only. Identifier of a message in the chat with the giveaway; the message could have been deleted
     * already. May be 0 if the message isn't sent yet.
     */
    public null|int $giveaway_message_id = null;

    /** `giveaway` only. Optional. True, if the giveaway was completed, but there was no user to win the prize */
    public null|bool $is_unclaimed = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}