<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a user allowing a bot to write messages after adding the bot to
 * the attachment menu or launching a Web App from a link.
 */
class writeAccessAllowed extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Optional. Name of the Web App which was launched from a link */
    public string $web_app_name;

    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
