<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the type of background.
 */
class backgroundType extends types {
    /** Keep all properties which has sub properties */
    private const subs = ['fill' => 'BPT\types\backgroundFill', 'document' => 'BPT\types\document'];

    /** Type of the background, could be `fill`, `wallpaper`, `pattern`, `chat_theme` */
    public string $type;

    /**
     * `pattern` and `fill` only. The background fill
     */
    public backgroundFill $fill;

    /**
     * `fill` and `wallpaper` only. Dimming of the background in dark themes, as a percentage; 0-100
     */
    public int $dark_theme_dimming;

    /**
     * `pattern` and `wallpaper` only. Document with the wallpaper
     */
    public document $document;

    /**
     * `wallpaper` only. True, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred
     * with radius 12
     */
    public bool $is_blurred;

    /**
     * `pattern` and `wallpaper` only. True, if the background moves slightly when the device is tilted
     */
    public bool $is_moving;

    /** `pattern` only. Intensity of the pattern when it is shown above the filled background; 0-100 */
    public int $intensity;

    /**
     * `pattern` only. True, if the background fill must be applied only to the pattern itself. All other pixels are
     * black in this case. For dark themes only
     */
    public bool $is_inverted;

    /** `chat_theme` only. Name of the chat theme, which is usually an emoji */
    public string $theme_name;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}