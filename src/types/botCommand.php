<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a bot command.
 * @method self setCommand(string $value)
 * @method self setDescription(string $value)
 */
class botCommand extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [];

    /** Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores. */
    public string $command;

    /** Description of the command; 1-256 characters. */
    public string $description;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
