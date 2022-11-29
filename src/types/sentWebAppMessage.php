<?php

namespace BPT\types;

use stdClass;

/**
 * Describes an inline message sent by a Web App on behalf of a user.
 */
class sentWebAppMessage extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the
     * message.
     */
    public null|string $inline_message_id = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
