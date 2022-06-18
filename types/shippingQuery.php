<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about an incoming shipping query.
 */
class shippingQuery extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['from' => 'BPT\types\user', 'shipping_address' => 'BPT\types\shippingAddress'];

	/** Unique query identifier */
	public string $id;

	/** User who sent the query */
	public user $from;

	/** Bot specified invoice payload */
	public string $invoice_payload;

	/** User specified shipping address */
	public shippingAddress $shipping_address;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
