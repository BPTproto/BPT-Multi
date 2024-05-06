<?php

namespace BPT\types;

use stdClass;

/**
 * Contains information about the start page settings of a Telegram Business account.
 */
class businessIntro extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['sticker' => 'BPT\types\sticker'];

    /** Optional. Title text of the business intro */
    public null|string $title = null;

    /** Optional. Message text of the business intro */
    public null|string $message = null;

    /** Optional. Sticker of the business intro */
    public null|sticker $sticker = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
