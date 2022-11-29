<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique
 * identifiers.
 */
class game extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'array' => ['photo' => 'BPT\types\photoSize', 'text_entities' => 'BPT\types\messageEntity'],
        'animation' => 'BPT\types\animation',
    ];

    /** Title of the game */
    public string $title;

    /** Description of the game */
    public string $description;

    /**
     * Photo that will be displayed in the game message in chats.
     * @var photoSize[]
     */
    public array $photo;

    /**
     * Optional. Brief description of the game or high scores included in the game message. Can be automatically
     * edited to include current high scores for the game when the bot calls setGameScore, or manually edited using
     * editMessageText. 0-4096 characters.
     */
    public null|string $text = null;

    /**
     * Optional. Special entities that appear in text, such as usernames, URLs, bot commands, etc.
     * @var messageEntity[]
     */
    public null|array $text_entities = null;

    /** Optional. Animation that will be displayed in the game message in chats. Upload via BotFather */
    public null|animation $animation = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
