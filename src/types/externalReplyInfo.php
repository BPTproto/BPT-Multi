<?php

namespace BPT\types;

use stdClass;

/**
 * This object contains information about a message that is being replied to, which may come from another chat or
 * forum topic.
 */
class externalReplyInfo extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'origin' => 'BPT\types\messageOrigin',
        'chat' => 'BPT\types\chat',
        'link_preview_options' => 'BPT\types\linkPreviewOptions',
        'animation' => 'BPT\types\animation',
        'audio' => 'BPT\types\audio',
        'document' => 'BPT\types\document',
        'array' => ['photo' => 'BPT\types\photoSize'],
        'sticker' => 'BPT\types\sticker',
        'story' => 'BPT\types\story',
        'video' => 'BPT\types\video',
        'video_note' => 'BPT\types\videoNote',
        'voice' => 'BPT\types\voice',
        'contact' => 'BPT\types\contact',
        'dice' => 'BPT\types\dice',
        'game' => 'BPT\types\game',
        'giveaway' => 'BPT\types\giveaway',
        'giveaway_winners' => 'BPT\types\giveawayWinners',
        'invoice' => 'BPT\types\invoice',
        'location' => 'BPT\types\location',
        'poll' => 'BPT\types\poll',
        'venue' => 'BPT\types\venue',
    ];

    /** Origin of the message replied to by the given message */
    public messageOrigin $origin;

    /** Optional. Chat the original message belongs to. Available only if the chat is a supergroup or a channel. */
    public null|chat $chat = null;

    /**
     * Optional. Unique message identifier inside the original chat. Available only if the original chat is a
     * supergroup or a channel.
     */
    public null|int $message_id = null;

    /** Optional. Options used for link preview generation for the original message, if it is a text message */
    public null|linkPreviewOptions $link_preview_options = null;

    /** Optional. Message is an animation, information about the animation */
    public null|animation $animation = null;

    /** Optional. Message is an audio file, information about the file */
    public null|audio $audio = null;

    /** Optional. Message is a general file, information about the file */
    public null|document $document = null;

    /**
     * Optional. Message is a photo, available sizes of the photo
     * @var photoSize[]
     */
    public null|array $photo = null;

    /** Optional. Message is a sticker, information about the sticker */
    public null|sticker $sticker = null;

    /** Optional. Message is a forwarded story */
    public null|story $story = null;

    /** Optional. Message is a video, information about the video */
    public null|video $video = null;

    /** Optional. Message is a video note, information about the video message */
    public null|videoNote $video_note = null;

    /** Optional. Message is a voice message, information about the file */
    public null|voice $voice = null;

    /** Optional. True, if the message media is covered by a spoiler animation */
    public null|bool $has_media_spoiler = null;

    /** Optional. Message is a shared contact, information about the contact */
    public null|contact $contact = null;

    /** Optional. Message is a dice with random value */
    public null|dice $dice = null;

    /** Optional. Message is a game, information about the game. More about games » */
    public null|game $game = null;

    /** Optional. Message is a scheduled giveaway, information about the giveaway */
    public null|giveaway $giveaway = null;

    /** Optional. A giveaway with public winners was completed */
    public null|giveawayWinners $giveaway_winners = null;

    /** Optional. Message is an invoice for a payment, information about the invoice. More about payments » */
    public null|invoice $invoice = null;

    /** Optional. Message is a shared location, information about the location */
    public null|location $location = null;

    /** Optional. Message is a native poll, information about the poll */
    public null|poll $poll = null;

    /** Optional. Message is a venue, information about the venue */
    public null|venue $venue = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
