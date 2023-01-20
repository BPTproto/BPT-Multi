<?php

namespace BPT\types;

use BPT\telegram\telegram;
use stdClass;

/**
 * This object represents an incoming inline query. When the user sends an empty query, your bot could return
 * some default or trending results.
 */
class inlineQuery extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['from' => 'BPT\types\user', 'location' => 'BPT\types\location'];

    /** Unique identifier for this query */
    public string $id;

    /** Sender */
    public user $from;

    /** Text of the query (up to 256 characters) */
    public string $query;

    /** Offset of the results to be returned, can be controlled by the bot */
    public string $offset;

    /**
     * Optional. Type of the chat from which the inline query was sent. Can be either “sender” for a private chat
     * with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type
     * should be always known for requests sent from official clients and most third-party clients, unless the
     * request was sent from a secret chat
     */
    public null|string $chat_type = null;

    /** Optional. Sender location, only for bots that request user location */
    public null|location $location = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    public function answer(array $results, int|null $cache_time = null, bool|null $is_personal = null, string|null $next_offset = null, string|null $switch_pm_text = null, string|null $switch_pm_parameter = null): responseError|bool {
        return telegram::answerInlineQuery($results, $this->id, $cache_time, $is_personal, $next_offset, $switch_pm_text, $switch_pm_parameter);
    }
}
