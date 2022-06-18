<?php

namespace BPT\types;

use stdClass;

/**
 * Represents an animation file (GIF or H.264/MPEG-4 AVC video without sound) to be sent.
 */
class inputMediaAnimation extends types {
	/** Keep all of properties which has sub properties */
	private const subs = ['thumb' => 'BPT\types\inputFile'];

	/** Type of the result, must be animation */
	public string $type;

	/**
	 * File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP
	 * URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new
	 * one using multipart/form-data under <file_attach_name> name. More info on Sending Files »
	 */
	public string $media;

	/**
	 * Optional. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported
	 * server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and
	 * height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't
	 * be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the
	 * thumbnail was uploaded using multipart/form-data under <file_attach_name>. More info on Sending Files »
	 */
	public inputFile $thumb;

	/** Optional. Caption of the animation to be sent, 0-1024 characters after entities parsing */
	public string $caption;

	/** Optional. Mode for parsing entities in the animation caption. See formatting options for more details. */
	public string $parse_mode;

	/** Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode */
	public array $caption_entities;

	/** Optional. Animation width */
	public int $width;

	/** Optional. Animation height */
	public int $height;

	/** Optional. Animation duration in seconds */
	public int $duration;


	public function __construct(stdClass $update) {
		parent::__construct($update, self::subs);
	}
}
