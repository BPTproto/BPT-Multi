<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about a poll.
 */
class poll extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['options' => 'BPT\types\pollOption', 'explanation_entities' => 'BPT\types\messageEntity'],
    ];

    /** Unique poll identifier */
    public string $id;

    /** Poll question, 1-300 characters */
    public string $question;

    /**
     * List of poll options
     * @var pollOption[]
     */
    public array $options;

    /** Total number of users that voted in the poll */
    public int $total_voter_count;

    /** True, if the poll is closed */
    public null|bool $is_closed = null;

    /** True, if the poll is anonymous */
    public null|bool $is_anonymous = null;

    /** Poll type, currently can be “regular” or “quiz” */
    public string $type;

    /** True, if the poll allows multiple answers */
    public null|bool $allows_multiple_answers = null;

    /**
     * Optional. 0-based identifier of the correct answer option. Available only for polls in the quiz mode, which
     * are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
     */
    public null|int $correct_option_id = null;

    /**
     * Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style
     * poll, 0-200 characters
     */
    public null|string $explanation = null;

    /**
     * Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
     * @var messageEntity[]
     */
    public null|array $explanation_entities = null;

    /** Optional. Amount of time in seconds the poll will be active after creation */
    public null|int $open_period = null;

    /** Optional. Point in time (Unix timestamp) when the poll will be automatically closed */
    public null|int $close_date = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
