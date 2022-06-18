<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one row of the high scores table for a game.
 */
class gameHighScore extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['user' => 'BPT\types\user'];

	/** Position in high score table for the game */
	public int $position;

	/** User */
	public user $user;

	/** Score */
	public int $score;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
