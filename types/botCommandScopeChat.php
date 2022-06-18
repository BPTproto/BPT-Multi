<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the scope of bot commands, covering a specific chat.
 */
class botCommandScopeChat extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** Scope type, must be chat */
	public string $type;

	/** Unique identifier for the target chat or username of the target supergroup (in the format supergroupusername) */
	public int $chat_id;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
