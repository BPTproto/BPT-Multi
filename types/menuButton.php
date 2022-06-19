<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the bot's menu button in a private chat. It should be one of
 */
class menuButton extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
