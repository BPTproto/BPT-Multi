<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about an edited forum topic
 */
class forumTopicEdited extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Optional. New name of the topic, if it was edited */
    public string $name;

    /** Optional. New identifier of the custom emoji shown as the topic icon, if it was edited; an empty string if the icon was removed */
    public null|string $icon_custom_emoji_id = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
