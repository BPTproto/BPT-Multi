<?php

namespace BPT\types;

use stdClass;

/**
 * Describes the options used for link preview generation.
 */
class linkPreviewOptions extends types {
    /** Keep all properties which has sub properties */
    private const subs = [];

    /** Optional. True, if the link preview is disabled */
    public null|bool $is_disabled = null;

    /** Optional. URL to use for the link preview. If empty, then the first URL found in the message text will be used */
    public null|string $url = null;

    /**
     * Optional. True, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly
     * specified or media size change isn't supported for the preview
     */
    public null|bool $prefer_small_media = null;

    /**
     * Optional. True, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't
     * explicitly specified or media size change isn't supported for the preview
     */
    public null|bool $prefer_large_media = null;

    /**
     * Optional. True, if the link preview must be shown above the message text; otherwise, the link preview will be
     * shown below the message text
     */
    public null|bool $show_above_text = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
