<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a new forum topic created in the chat.
 */
class forumTopicCreated extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Name of the topic */
    public string $name;

    /** Color of the topic icon in RGB format */
    public int $icon_color;

    /** Optional. Unique identifier of the custom emoji shown as the topic icon */
    public null|string $icon_custom_emoji_id = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
