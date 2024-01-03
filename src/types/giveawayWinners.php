<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a message about the completion of a giveaway with public winners.
 */
class giveawayWinners extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['chat' => 'BPT\types\chat', 'array' => ['winners' => 'BPT\types\user']];

    /** The chat that created the giveaway */
    public chat $chat;

    /** Identifier of the messsage with the giveaway in the chat */
    public int $giveaway_message_id;

    /** Point in time (Unix timestamp) when winners of the giveaway were selected */
    public int $winners_selection_date;

    /** Total number of winners in the giveaway */
    public int $winner_count;

    /**
     * List of up to 100 winners of the giveaway
     * @var user[]
     */
    public array $winners;

    /** Optional. The number of other chats the user had to join in order to be eligible for the giveaway */
    public null|int $additional_chat_count = null;

    /** Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for */
    public null|int $premium_subscription_month_count = null;

    /** Optional. Number of undistributed prizes */
    public null|int $unclaimed_prize_count = null;

    /** Optional. True, if only users who had joined the chats after the giveaway started were eligible to win */
    public null|bool $only_new_members = null;

    /** Optional. True, if the giveaway was canceled because the payment for it was refunded */
    public null|bool $was_refunded = null;

    /** Optional. Description of additional giveaway prize */
    public null|string $prize_description = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
