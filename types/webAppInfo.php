<?php

namespace BPT\types;

use stdClass;

/**
 * Describes a Web App.
 */
class webAppInfo extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps */
    public string $url;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
