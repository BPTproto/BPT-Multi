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
        'array' => [
            'entities' => 'BPT\types\messageEntity',
            'photo' => 'BPT\types\photoSize',
            'caption_entities' => 'BPT\types\messageEntity',
            'new_chat_members' => 'BPT\types\user',
            'new_chat_photo' => 'BPT\types\photoSize',
        ],
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
        'forum_topic_created' => 'BPT\types\forumTopicCreated',
        'forum_topic_closed' => 'BPT\types\forumTopicClosed',
        'forum_topic_reopened' => 'BPT\types\forumTopicReopened',
        'video_chat_scheduled' => 'BPT\types\videoChatScheduled',
        'video_chat_started' => 'BPT\types\videoChatStarted',
        'video_chat_ended' => 'BPT\types\videoChatEnded',
        'video_chat_participants_invited' => 'BPT\types\videoChatParticipantsInvited',
        'web_app_data' => 'BPT\types\webAppData',
        'reply_markup' => 'BPT\types\inlineKeyboardMarkup',
    ];

    /** Unique message identifier inside this chat */
    public int $id;

    /** Unique message identifier inside this chat */
    public int $message_id;

    /** Optional. Unique identifier of a message thread to which the message belongs; for supergroups only */
    public null|int $message_thread_id = null;

    /**
     * Optional. Sender of the message; empty for messages sent to channels. For backward compatibility, the field
     * contains a fake sender user in non-channel chats, if the message was sent on behalf of a chat.
     */
    public null|user $from = null;

    /**
     * Optional. Sender of the message, sent on behalf of a chat. For example, the channel itself for channel posts,
     * the supergroup itself for messages from anonymous group administrators, the linked channel for messages
     * automatically forwarded to the discussion group. For backward compatibility, the field from contains a fake
     * sender user in non-channel chats, if the message was sent on behalf of a chat.
     */
    public null|chat $sender_chat = null;

    /** Date the message was sent in Unix time */
    public int $date;

    /** Conversation the message belongs to */
    public chat $chat;

    /** Optional. For forwarded messages, sender of the original message */
    public null|user $forward_from = null;

    /**
     * Optional. For messages forwarded from channels or from anonymous administrators, information about the
     * original sender chat
     */
    public null|chat $forward_from_chat = null;

    /** Optional. For messages forwarded from channels, identifier of the original message in the channel */
    public null|int $forward_from_message_id = null;

    /**
     * Optional. For forwarded messages that were originally sent in channels or by an anonymous chat administrator,
     * signature of the message sender if present
     */
    public null|string $forward_signature = null;

    /**
     * Optional. Sender's name for messages forwarded from users who disallow adding a link to their account in
     * forwarded messages
     */
    public null|string $forward_sender_name = null;

    /** Optional. For forwarded messages, date the original message was sent in Unix time */
    public null|int $forward_date = null;

    /** Optional. True, if the message is sent to a forum topic */
    public null|bool $is_topic_message = null;

    /**
     * Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion
     * group
     */
    public null|bool $is_automatic_forward = null;

    /**
     * Optional. For replies, the original message. Note that the Message object in this field will not contain
     * further reply_to_message fields even if it itself is a reply.
     */
    public null|message $reply_to_message = null;

    /** Optional. Bot through which the message was sent */
    public null|user $via_bot = null;

    /** Optional. Date the message was last edited in Unix time */
    public null|int $edit_date = null;

    /** Optional. True, if the message can't be forwarded */
    public null|bool $has_protected_content = null;

    /** Optional. The unique identifier of a media message group this message belongs to */
    public null|string $media_group_id = null;

    /**
     * Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group
     * administrator
     */
    public null|string $author_signature = null;

    /** Optional. For text messages, the actual UTF-8 text of the message */
    public null|string $text = null;

    /** Optional. If user message was a commend , this parameter will be the commend */
    public string|null $commend = null;

    /** Optional. If user message was a commend , this parameter will be the commend username(if exist) */
    public string|null $commend_username = null;

    /** Optional. If user message was a commend , this parameter will be the commend payload(if exist) */
    public string|null $commend_payload = null;

    /**
     * Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @var messageEntity[]
     */
    public null|array $entities = null;

    /**
     * Optional. Message is an animation, information about the animation. For backward compatibility, when this
     * field is set, the document field will also be set
     */
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

    /** Optional. Message is a video, information about the video */
    public null|video $video = null;

    /** Optional. Message is a video note, information about the video message */
    public null|videoNote $video_note = null;

    /** Optional. Message is a voice message, information about the file */
    public null|voice $voice = null;

    /** Optional. Caption for the animation, audio, document, photo, video or voice */
    public null|string $caption = null;

    /**
     * Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear
     * in the caption
     * @var messageEntity[]
     */
    public null|array $caption_entities = null;

    /** Optional. Message is a shared contact, information about the contact */
    public null|contact $contact = null;

    /** Optional. Message is a dice with random value */
    public null|dice $dice = null;

    /** Optional. Message is a game, information about the game. More about games » */
    public null|game $game = null;

    /** Optional. Message is a native poll, information about the poll */
    public null|poll $poll = null;

    /**
     * Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set,
     * the location field will also be set
     */
    public null|venue $venue = null;

    /** Optional. Message is a shared location, information about the location */
    public null|location $location = null;

    /**
     * Optional. New members that were added to the group or supergroup and information about them (the bot itself
     * may be one of these members)
     * @var user[]
     */
    public null|array $new_chat_members = null;

    /** Optional. A member was removed from the group, information about them (this member may be the bot itself) */
    public null|user $left_chat_member = null;

    /** Optional. A chat title was changed to this value */
    public null|string $new_chat_title = null;

    /**
     * Optional. A chat photo was change to this value
     * @var photoSize[]
     */
    public null|array $new_chat_photo = null;

    /** Optional. Service message: the chat photo was deleted */
    public null|bool $delete_chat_photo = null;

    /** Optional. Service message: the group has been created */
    public null|bool $group_chat_created = null;

    /**
     * Optional. Service message: the supergroup has been created. This field can't be received in a message coming
     * through updates, because bot can't be a member of a supergroup when it is created. It can only be found in
     * reply_to_message if someone replies to a very first message in a directly created supergroup.
     */
    public null|bool $supergroup_chat_created = null;

    /**
     * Optional. Service message: the channel has been created. This field can't be received in a message coming
     * through updates, because bot can't be a member of a channel when it is created. It can only be found in
     * reply_to_message if someone replies to a very first message in a channel.
     */
    public null|bool $channel_chat_created = null;

    /** Optional. Service message: auto-delete timer settings changed in the chat */
    public null|messageAutoDeleteTimerChanged $message_auto_delete_timer_changed = null;

    /**
     * Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more
     * than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it.
     * But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for
     * storing this identifier.
     */
    public null|int $migrate_to_chat_id = null;

    /**
     * Optional. The supergroup has been migrated from a group with the specified identifier. This number may have
     * more than 32 significant bits and some programming languages may have difficulty/silent defects in
     * interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float
     * type are safe for storing this identifier.
     */
    public null|int $migrate_from_chat_id = null;

    /**
     * Optional. Specified message was pinned. Note that the Message object in this field will not contain further
     * reply_to_message fields even if it is itself a reply.
     */
    public null|message $pinned_message = null;

    /** Optional. Message is an invoice for a payment, information about the invoice. More about payments » */
    public null|invoice $invoice = null;

    /**
     * Optional. Message is a service message about a successful payment, information about the payment. More about
     * payments »
     */
    public null|successfulPayment $successful_payment = null;

    /** Optional. The domain name of the website on which the user has logged in. More about Telegram Login » */
    public null|string $connected_website = null;

    /** Optional. Telegram Passport data */
    public null|passportData $passport_data = null;

    /**
     * Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live
     * Location.
     */
    public null|proximityAlertTriggered $proximity_alert_triggered = null;

    /** Optional. Service message: forum topic created */
    public null|forumTopicCreated $forum_topic_created = null;

    /** Optional. Service message: forum topic closed */
    public null|forumTopicClosed $forum_topic_closed = null;

    /** Optional. Service message: forum topic reopened */
    public null|forumTopicReopened $forum_topic_reopened = null;

    /** Optional. Service message: video chat scheduled */
    public null|videoChatScheduled $video_chat_scheduled = null;

    /** Optional. Service message: video chat started */
    public null|videoChatStarted $video_chat_started = null;

    /** Optional. Service message: video chat ended */
    public null|videoChatEnded $video_chat_ended = null;

    /** Optional. Service message: new participants invited to a video chat */
    public null|videoChatParticipantsInvited $video_chat_participants_invited = null;

    /** Optional. Service message: data sent by a Web App */
    public null|webAppData $web_app_data = null;

    /** Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons. */
    public null|inlineKeyboardMarkup $reply_markup = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
