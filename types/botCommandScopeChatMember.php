<?php

namespace BPT\types;

use stdClass;

/**
 * Represents the scope of bot commands, covering a specific member of a group or supergroup chat.
 */
class botCommandScopeChatMember extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** Scope type, must be chat_member */
	public string $type;

	/** Unique identifier for the target chat or username of the target supergroup (in the format supergroupusername) */
	public int $chat_id;

	/** Unique identifier of the target user */
	public int $user_id;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
