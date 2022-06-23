<?php

namespace BPT\types;

use stdClass;

/**
 * This object represent a user's profile pictures.
 */
class userProfilePhotos extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['array' => ['photos' => 'BPT\types\photoSize']]];

    /** Total number of profile pictures the target user has */
    public int $total_count;

    /**
     * Requested profile pictures (in up to 4 sizes each)
     * @var photoSize[][]
     */
    public array $photos;


    public function __construct(stdClass $object) {
        parent::__construct($object, self::subs);
    }
}
