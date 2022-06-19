<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents a message.
 */
class message extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'from' => 'BPT\types\user',
        'sender_chat' => 'BPT\types\chat',
        'chat' => 'BPT\types\chat',
        'forward_from' => 'BPT\types\user',
        'forward_from_chat' => 'BPT\types\chat',
        'reply_to_message' => 'BPT\types\message',
        'via_bot' => 'BPT\types\user',
        'animation' => 'BPT\types\animation',
        'audio' => 'BPT\types\audio',
        'document' => 'BPT\types\document',
        'sticker' => 'BPT\types\sticker',
        'video' => 'BPT\types\video',
        'video_note' => 'BPT\types\videoNote',
        'voice' => 'BPT\types\voice',
        'contact' => 'BPT\types\contact',
        'dice' => 'BPT\types\dice',
        'game' => 'BPT\types\game',
        'poll' => 'BPT\types\poll',
        'venue' => 'BPT\types\venue',
        'location' => 'BPT\types\location',
        'left_chat_member' => 'BPT\types\user',
        'message_auto_delete_timer_changed' => 'BPT\types\messageAutoDeleteTimerChanged',
        'pinned_message' => 'BPT\types\message',
        'invoice' => 'BPT\types\invoice',
        'successful_payment' => 'BPT\types\successfulPayment',
        'passport_data' => 'BPT\types\passportData',
        'proximity_alert_triggered' => 'BPT\types\proximityAlertTriggered',
        'video_chat_scheduled' => 'BPT\types\videoChatScheduled',
        'video_chat_started' => 'BPT\types\videoChatStarted',
        'video_chat_ended' => 'BPT\types\videoChatEnded',
        'video_chat_participants_invited' => 'BPT\types\videoChatParticipantsInvited',
        'web_app_data' => 'BPT\types\webAppData',
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
    ];

    /** Unique message identifier inside this chat */
    public int $message_id;

    /**
     * Optional. Sender of the message; empty for messages sent to channels. For backward compatibility, the field
     * contains a fake sender user in non-channel chats, if the message was sent on behalf of a chat.
     */
    public user $from;

    /**
     * Optional. Sender of the message, sent on behalf of a chat. For example, the channel itself for channel posts,
     * the supergroup itself for messages from anonymous group administrators, the linked channel for messages
     * automatically forwarded to the discussion group.  For backward compatibility, the field from contains a fake
     * sender user in non-channel chats, if the message was sent on behalf of a chat.
     */
    public chat $sender_chat;

    /** Date the message was sent in Unix time */
    public int $date;

    /** Conversation the message belongs to */
    public chat $chat;

    /** Optional. For forwarded messages, sender of the original message */
    public user $forward_from;

    /**
     * Optional. For messages forwarded from channels or from anonymous administrators, information about the
     * original sender chat
     */
    public chat $forward_from_chat;

    /** Optional. For messages forwarded from channels, identifier of the original message in the channel */
    public int $forward_from_message_id;

    /**
     * Optional. For forwarded messages that were originally sent in channels or by an anonymous chat administrator,
     * signature of the message sender if present
     */
    public string $forward_signature;

    /**
     * Optional. Sender's name for messages forwarded from users who disallow adding a link to their account in
     * forwarded messages
     */
    public string $forward_sender_name;

    /** Optional. For forwarded messages, date the original message was sent in Unix time */
    public int $forward_date;

    /**
     * Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion
     * group
     */
    public bool $is_automatic_forward;

    /**
     * Optional. For replies, the original message. Note that the Message object in this field will not contain
     * further reply_to_message fields even if it itself is a reply.
     */
    public message $reply_to_message;

    /** Optional. Bot through which the message was sent */
    public user $via_bot;

    /** Optional. Date the message was last edited in Unix time */
    public int $edit_date;

    /** Optional. True, if the message can't be forwarded */
    public bool $has_protected_content;

    /** Optional. The unique identifier of a media message group this message belongs to */
    public string $media_group_id;

    /**
     * Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group
     * administrator
     */
    public string $author_signature;

    /** Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters */
    public string $text;

    /** Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text */
    public array $entities;

    /**
     * Optional. Message is an animation, information about the animation. For backward compatibility, when this
     * field is set, the document field will also be set
     */
    public animation $animation;

    /** Optional. Message is an audio file, information about the file */
    public audio $audio;

    /** Optional. Message is a general file, information about the file */
    public document $document;

    /** Optional. Message is a photo, available sizes of the photo */
    public array $photo;

    /** Optional. Message is a sticker, information about the sticker */
    public sticker $sticker;

    /** Optional. Message is a video, information about the video */
    public video $video;

    /** Optional. Message is a video note, information about the video message */
    public videoNote $video_note;

    /** Optional. Message is a voice message, information about the file */
    public voice $voice;

    /** Optional. Caption for the animation, audio, document, photo, video or voice, 0-1024 characters */
    public string $caption;

    /**
     * Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear
     * in the caption
     */
    public array $caption_entities;

    /** Optional. Message is a shared contact, information about the contact */
    public contact $contact;

    /** Optional. Message is a dice with random value */
    public dice $dice;

    /** Optional. Message is a game, information about the game. More about games » */
    public game $game;

    /** Optional. Message is a native poll, information about the poll */
    public poll $poll;

    /**
     * Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set,
     * the location field will also be set
     */
    public venue $venue;

    /** Optional. Message is a shared location, information about the location */
    public location $location;

    /**
     * Optional. New members that were added to the group or supergroup and information about them (the bot itself
     * may be one of these members)
     */
    public array $new_chat_members;

    /** Optional. A member was removed from the group, information about them (this member may be the bot itself) */
    public user $left_chat_member;

    /** Optional. A chat title was changed to this value */
    public string $new_chat_title;

    /** Optional. A chat photo was change to this value */
    public array $new_chat_photo;

    /** Optional. Service message: the chat photo was deleted */
    public bool $delete_chat_photo;

    /** Optional. Service message: the group has been created */
    public bool $group_chat_created;

    /**
     * Optional. Service message: the supergroup has been created. This field can't be received in a message coming
     * through updates, because bot can't be a member of a supergroup when it is created. It can only be found in
     * reply_to_message if someone replies to a very first message in a directly created supergroup.
     */
    public bool $supergroup_chat_created;

    /**
     * Optional. Service message: the channel has been created. This field can't be received in a message coming
     * through updates, because bot can't be a member of a channel when it is created. It can only be found in
     * reply_to_message if someone replies to a very first message in a channel.
     */
    public bool $channel_chat_created;

    /** Optional. Service message: auto-delete timer settings changed in the chat */
    public messageAutoDeleteTimerChanged $message_auto_delete_timer_changed;

    /**
     * Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more
     * than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it.
     * But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for
     * storing this identifier.
     */
    public int $migrate_to_chat_id;

    /**
     * Optional. The supergroup has been migrated from a group with the specified identifier. This number may have
     * more than 32 significant bits and some programming languages may have difficulty/silent defects in
     * interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float
     * type are safe for storing this identifier.
     */
    public int $migrate_from_chat_id;

    /**
     * Optional. Specified message was pinned. Note that the Message object in this field will not contain further
     * reply_to_message fields even if it is itself a reply.
     */
    public message $pinned_message;

    /** Optional. Message is an invoice for a payment, information about the invoice. More about payments » */
    public invoice $invoice;

    /**
     * Optional. Message is a service message about a successful payment, information about the payment. More about
     * payments »
     */
    public successfulPayment $successful_payment;

    /** Optional. The domain name of the website on which the user has logged in. More about Telegram Login » */
    public string $connected_website;

    /** Optional. Telegram Passport data */
    public passportData $passport_data;

    /**
     * Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live
     * Location.
     */
    public proximityAlertTriggered $proximity_alert_triggered;

    /** Optional. Service message: video chat scheduled */
    public videoChatScheduled $video_chat_scheduled;

    /** Optional. Service message: video chat started */
    public videoChatStarted $video_chat_started;

    /** Optional. Service message: video chat ended */
    public videoChatEnded $video_chat_ended;

    /** Optional. Service message: new participants invited to a video chat */
    public videoChatParticipantsInvited $video_chat_participants_invited;

    /** Optional. Service message: data sent by a Web App */
    public webAppData $web_app_data;

    /** Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons. */
    public inlineKeyboardMarkup $reply_markup;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
