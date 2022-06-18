<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a service message about new members invited to a video chat.
 */
class videoChatParticipantsInvited extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** New members that were invited to the video chat */
	public array $users;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
