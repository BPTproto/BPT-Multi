<?php

namespace BPT\types;

use BPT\telegram\telegram;
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


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }

    /**
     * If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API
     * will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries.
     * On success, True is returned.
     *
     * @param bool        $ok
     * @param null|array  $shipping_options
     * @param null|string $error_message
     * @param bool|null   $answer
     *
     * @return responseError|bool
     */
    public function answer (bool $ok, null|array $shipping_options = null, string|null $error_message = null, bool $answer = null): responseError|bool {
        return telegram::answerShippingQuery($ok, $this->id, $shipping_options, $error_message, answer: $answer);
    }
}
