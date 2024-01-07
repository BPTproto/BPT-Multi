<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a message about a scheduled giveaway.
 */
class giveaway extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['array' => ['chats' => 'BPT\types\chat']];

    /**
     * The list of chats which the user must join to participate in the giveaway
     * @var chat[]
     */
    public array $chats;

    /** Point in time (Unix timestamp) when winners of the giveaway will be selected */
    public int $winners_selection_date;

    /** The number of users which are supposed to be selected as winners of the giveaway */
    public int $winner_count;

    /** Optional. True, if only users who join the chats after the giveaway started should be eligible to win */
    public null|bool $only_new_members = null;

    /** Optional. True, if the list of giveaway winners will be visible to everyone */
    public null|bool $has_public_winners = null;

    /** Optional. Description of additional giveaway prize */
    public null|string $prize_description = null;

    /**
     * Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which eligible
     * users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone
     * number that was bought on Fragment can always participate in giveaways.
     * @var string[]
     */
    public null|array $country_codes = null;

    /** Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for */
    public null|int $premium_subscription_month_count = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
