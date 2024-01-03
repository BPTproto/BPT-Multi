<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a boost removed from a chat.
 */
class chatBoostRemoved extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat', 'source' => 'BPT\types\chatBoostSource'];

    /** Chat which was boosted */
    public chat $chat;

    /** Unique identifier of the boost */
    public string $boost_id;

    /** Point in time (Unix timestamp) when the boost was removed */
    public int $remove_date;

    /** Source of the removed boost */
    public chatBoostSource $source;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
