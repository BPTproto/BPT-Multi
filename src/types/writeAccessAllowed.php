<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about a user allowing a bot to write messages after adding it to the
 * attachment menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the
 * method requestWriteAccess.
 */
class writeAccessAllowed extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /**
     * Optional. True, if the access was granted after the user accepted an explicit request from a Web App sent by
     * the method requestWriteAccess
     */
    public bool $from_request;

    /** Optional. Name of the Web App, if the access was granted when the Web App was launched from a link */
    public string $web_app_name;

    /** Optional. True, if the access was granted when the bot was added to the attachment or side menu */
    public bool $from_attachment_menu;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
