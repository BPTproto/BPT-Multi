<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a bot command.
 */
class botCommand extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores. */
    public string $command;

    /** Description of the command; 1-256 characters. */
    public string $description;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
