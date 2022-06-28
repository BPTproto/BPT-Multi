<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the content of a service message, sent whenever a user in the chat triggers a proximity
 * alert set by another user.
 */
class proximityAlertTriggered extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['traveler' => 'BPT\types\user', 'watcher' => 'BPT\types\user'];

    /** User that triggered the alert */
    public user $traveler;

    /** User that set the alert */
    public user $watcher;

    /** The distance between the users */
    public int $distance;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
