<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 */
class messageEntity extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['user' => 'BPT\types\user'];

    /**
     * Type of the entity. Currently, can be “mention” (username), “hashtag” (#hashtag), “cashtag”
     * ($USD), “bot_command” (/startjobs_bot), “url” (https://telegram.org), “email”
     * (do-not-replytelegram.org), “phone_number” (+1-212-555-0123), “bold” (bold text), “italic” (italic
     * text), “underline” (underlined text), “strikethrough” (strikethrough text), “spoiler” (spoiler
     * message), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text
     * URLs), “text_mention” (for users without usernames)
     */
    public string $type;

    /** Offset in UTF-16 code units to the start of the entity */
    public int $offset;

    /** Length of the entity in UTF-16 code units */
    public int $length;

    /** Optional. For “text_link” only, URL that will be opened after user taps on the text */
    public string $url;

    /** Optional. For “text_mention” only, the mentioned user */
    public user $user;

    /** Optional. For “pre” only, the programming language of the entity text */
    public string $language;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
