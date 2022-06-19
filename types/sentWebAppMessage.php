<?php

namespace BPT\types;

use stdClass;

/**
 * Contains information about an inline message sent by a Web App on behalf of a user.
 */
class sentWebAppMessage extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the
     * message.
     */
    public string $inline_message_id;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
