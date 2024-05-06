<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the way a background is filled based on the selected colors.
 */
class backgroundFill extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Type of the background fill, could be `solid`, `gradient`, `freeform_gradient` */
    public string $type;

    /** `solid` only. Solid The color of the background fill in the RGB24 format */
    public int $color;

    /** `gradient` only. Gradient Top color of the gradient in the RGB24 format */
    public int $top_color;

    /** `gradient` only. Gradient Bottom color of the gradient in the RGB24 format */
    public int $bottom_color;

    /** `gradient` only. Clockwise rotation angle of the background fill in degrees; 0-359 */
    public int $rotation_angle;

    /**
     * `freeform_gradient` only. A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
     * @var int[]
     */
    public array $colors;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
