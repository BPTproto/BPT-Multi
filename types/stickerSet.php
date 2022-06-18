<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a sticker set.
 */
class stickerSet extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['thumb' => 'BPT\types\photoSize'];

	/** Sticker set name */
	public string $name;

	/** Sticker set title */
	public string $title;

	/** True, if the sticker set contains animated stickers */
	public bool $is_animated;

	/** True, if the sticker set contains video stickers */
	public bool $is_video;

	/** True, if the sticker set contains masks */
	public bool $contains_masks;

	/** List of all set stickers */
	public array $stickers;

	/** Optional. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format */
	public photoSize $thumb;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
