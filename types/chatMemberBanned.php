<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a chat member that was banned in the chat and can't return to the chat or view chat messages.
 */
class chatMemberBanned extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['user' => 'BPT\types\user'];

	/** The member's status in the chat, always “kicked” */
	public string $status;

	/** Information about the user */
	public user $user;

	/** Date when restrictions will be lifted for this user; unix time. If 0, then the user is banned forever */
	public int $until_date;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
