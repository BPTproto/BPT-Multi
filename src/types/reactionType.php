<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the type of reaction
 */
class reactionType extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Can be one of "emoji" or "custom_emoji" */
    public string $type;

    /**
     * `emoji` only. Currently, it can be one of "👍", "👎", "❤", "🔥", "🥰", "👏", "😁", "🤔",
     * "🤯", "😱", "🤬", "😢", "🎉", "🤩", "🤮", "💩", "🙏", "👌", "🕊", "🤡", "🥱",
     * "🥴", "😍", "🐳", "❤‍🔥", "🌚", "🌭", "💯", "🤣", "⚡", "🍌", "🏆", "💔", "🤨",
     * "😐", "🍓", "🍾", "💋", "🖕", "😈", "😴", "😭", "🤓", "👻", "👨‍💻", "👀", "🎃",
     * "🙈", "😇", "😨", "🤝", "✍", "🤗", "🫡", "🎅", "🎄", "☃", "💅", "🤪", "🗿", "🆒",
     * "💘", "🙉", "🦄", "😘", "💊", "🙊", "😎", "👾", "🤷‍♂", "🤷", "🤷‍♀", "😡"
     */
    public string $emoji;

    /** `custom_emoji` only. Custom emoji identifier */
    public string $custom_emoji;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
