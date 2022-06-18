<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a menu button, which opens the bot's list of commands.
 */
class menuButtonCommands extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** Type of the button, must be commands */
	public string $type;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
