<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a chat member that has no additional privileges or restrictions.
 */
class chatMemberMember extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['user' => 'BPT\types\user'];

	/** The member's status in the chat, always “member” */
	public string $status;

	/** Information about the user */
	public user $user;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
