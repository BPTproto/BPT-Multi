<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a result of an inline query that was chosen by the user and sent to their chat partner.
 */
class chosenInlineResult extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['from' => 'BPT\types\user', 'location' => 'BPT\types\location'];

    /** The unique identifier for the result that was chosen */
    public string $result_id;

    /** The user that chose the result */
    public user $from;

    /** Optional. Sender location, only for bots that require user location */
    public null|location $location = null;

    /**
     * Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the
     * message. Will be also received in callback queries and can be used to edit the message.
     */
    public null|string $inline_message_id = null;

    /** The query that was used to obtain the result */
    public string $query;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
