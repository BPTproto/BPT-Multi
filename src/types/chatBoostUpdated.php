<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a boost added to a chat or changed.
 */
class chatBoostUpdated extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat', 'boost' => 'BPT\types\chatBoost'];

    /** Chat which was boosted */
    public chat $chat;

    /** Information about the chat boost */
    public chatBoost $boost;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
