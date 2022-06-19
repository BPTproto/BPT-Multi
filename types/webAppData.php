<?php

namespace BPT\types;

use stdClass;

/**
 * Contains data sent from a Web App to the bot.
 */
class webAppData extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** The data. Be aware that a bad client can send arbitrary data in this field. */
    public string $data;

    /**
     * Text of the web_app keyboard button, from which the Web App was opened. Be aware that a bad client can send
     * arbitrary data in this field.
     */
    public string $button_text;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
