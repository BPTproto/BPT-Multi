<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one shipping option.
 */
class shippingOption extends types {
	/** Keep all of properties which has sub properties */
	private const subs = [];

	/** Shipping option identifier */
	public string $id;

	/** Option title */
	public string $title;

	/** List of price portions */
	public array $prices;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
