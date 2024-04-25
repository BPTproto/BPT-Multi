<?php

namespace BPT\types;

use stdClass;

/**
 * This object is received when messages are deleted from a connected business account.
 */
class businessMessagesDeleted extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat'];

    /** Unique identifier of the business connection */
    public string $business_connection_id;

    /**
     * Information about a chat in the business account. The bot may not have access to the chat or the corresponding
     * user.
     */
    public chat $chat;

    /**
     * A JSON-serialized list of identifiers of deleted messages in the chat of the business account
     * @var int[]
     */
    public array $message_ids;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
