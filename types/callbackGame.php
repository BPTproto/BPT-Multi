<?php

namespace BPT\types;

use stdClass;

/**
 * A placeholder, currently holds no information. Use BotFather to set up your game.
 */
class callbackGame extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
