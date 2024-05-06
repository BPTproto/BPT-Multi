<?php

namespace BPT\types;

use BPT\constants\chatMemberStatus;
use BPT\telegram\telegram;
use stdClass;

/**
 * This object represents a message.
 */
class message extends types {
    /** Keep all properties which has sub properties */
    protected const subs = [
        'from' => 'BPT\types\user',
        'sender_chat' => 'BPT\types\chat',
        'sender_business_bot' => 'BPT\types\user',
        'chat' => 'BPT\types\chat',
        'forward_from' => 'BPT\types\user',
        'forward_from_chat' => 'BPT\types\chat',
        'forward_origin' => 'BPT\types\messageOrigin',
        'reply_to_message' => 'BPT\types\message',
        'external_reply' => 'BPT\types\externalReplyInfo',
        'quote' => 'BPT\types\textQuote',
        'reply_to_story' => 'BPT\types\story',
        'via_bot' => 'BPT\types\user',
        'array' => [
            'entities' => 'BPT\types\messageEntity',
            'photo' => 'BPT\types\photoSize',
            'caption_entities' => 'BPT\types\messageEntity',
            'new_chat_members' => 'BPT\types\user',
            'new_chat_photo' => 'BPT\types\photoSize',
        ],
        'link_preview_options' => 'BPT\types\linkPreviewOptions',
        'animation' => 'BPT\types\animation',
        'audio' => 'BPT\types\audio',
        'document' => 'BPT\types\document',
        'sticker' => 'BPT\types\sticker',
        'story' => 'BPT\types\story',
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
        'pinned_message' => 'BPT\types\maybeInaccessibleMessage',
        'invoice' => 'BPT\types\invoice',
        'successful_payment' => 'BPT\types\successfulPayment',
        'users_shared' => 'BPT\types\usersShared',
        'user_shared' => 'BPT\types\userShared',
        'chat_shared' => 'BPT\types\chatShared',
        'write_access_allowed' => 'BPT\types\writeAccessAllowed',
        'passport_data' => 'BPT\types\passportData',
        'proximity_alert_triggered' => 'BPT\types\proximityAlertTriggered',
        'boost_added' => 'BPT\types\chatBoostAdded',
        'forum_topic_created' => 'BPT\types\forumTopicCreated',
        'forum_topic_edited' => 'BPT\types\forumTopicEdited',
        'forum_topic_closed' => 'BPT\types\forumTopicClosed',
        'forum_topic_reopened' => 'BPT\types\forumTopicReopened',
        'general_forum_topic_hidden' => 'BPT\types\generalForumTopicHidden',
        'general_forum_topic_unhidden' => 'BPT\types\generalForumTopicUnhidden',
        'giveaway_created' => 'BPT\types\giveawayCreated',
        'giveaway' => 'BPT\types\giveaway',
        'giveaway_winners' => 'BPT\types\giveawayWinners',
        'giveaway_completed' => 'BPT\types\giveawayCompleted',
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

    /** Optional. If the sender of the message boosted the chat, the number of boosts added by the user */
    public null|int $sender_boost_count = null;

    /**
     * Optional. The bot that actually sent the message on behalf of the business account. Available only for
     * outgoing messages sent on behalf of the connected business account.
     */
    public null|user $sender_business_bot = null;

    /** Date the message was sent in Unix time. It is always a positive number, representing a valid date. */
    public int $date;

    /**
     * Optional. Unique identifier of the business connection from which the message was received. If non-empty, the
     * message belongs to a chat of the corresponding business account that is independent from any potential bot
     * chat which might share the same identifier.
     */
    public null|string $business_connection_id = null;

    /** Chat the message belongs to */
    public chat $chat;

    /**
     * Optional. For forwarded messages, sender of the original message
     *
     * @deprecated used forward_origin instead
     */
    public null|user $forward_from = null;

    /**
     * Optional. For messages forwarded from channels or from anonymous administrators, information about the
     * original sender chat
     *
     * @deprecated used forward_origin instead
     */
    public null|chat $forward_from_chat = null;

    /**
     * Optional. For messages forwarded from channels, identifier of the original message in the channel
     *
     * @deprecated used forward_origin instead
     */
    public null|int $forward_from_message_id = null;

    /**
     * Optional. For forwarded messages that were originally sent in channels or by an anonymous chat administrator,
     * signature of the message sender if present
     *
     * @deprecated used forward_origin instead
     */
    public null|string $forward_signature = null;

    /**
     * Optional. Sender's name for messages forwarded from users who disallow adding a link to their account in
     * forwarded messages
     *
     * @deprecated used forward_origin instead
     */
    public null|string $forward_sender_name = null;

    /**
     * Optional. For forwarded messages, date the original message was sent in Unix time
     * @deprecated used forward_origin instead
     */
    public null|int $forward_date = null;

    /** Optional. Information about the original message for forwarded messages */
    public null|messageOrigin $forward_origin = null;

    /** Optional. True, if the message is sent to a forum topic */
    public null|bool $is_topic_message = null;

    /**
     * Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion
     * group
     */
    public null|bool $is_automatic_forward = null;

    /**
     * Optional. For replies in the same chat and message thread, the original message. Note that the Message object
     * in this field will not contain further reply_to_message fields even if it itself is a reply.
     */
    public null|message $reply_to_message = null;

    /**
     * Optional. Information about the message that is being replied to, which may come from another chat or forum
     * topic
     */
    public null|externalReplyInfo $external_reply = null;

    /** Optional. For replies that quote part of the original message, the quoted part of the message */
    public null|textQuote $quote = null;

    /** Optional. For replies to a story, the original story */
    public null|story $reply_to_story = null;

    /** Optional. Bot through which the message was sent */
    public null|user $via_bot = null;

    /** Optional. Date the message was last edited in Unix time */
    public null|int $edit_date = null;

    /** Optional. True, if the message can't be forwarded */
    public null|bool $has_protected_content = null;

    /**
     * Optional. True, if the message was sent by an implicit action, for example, as an away or a greeting business
     * message, or as a scheduled message
     */
    public null|bool $is_from_offline = null;

    /** Optional. The unique identifier of a media message group this message belongs to */
    public null|string $media_group_id = null;

    /**
     * Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group
     * administrator
     */
    public null|string $author_signature = null;

    /** Optional. For text messages, the actual UTF-8 text of the message */
    public null|string $text = null;

    /** Optional. If user message was a command , this parameter will be the command */
    public string|null $command = null;

    /** Optional. If user message was a command , this parameter will be the command username(if exist) */
    public string|null $command_username = null;

    /** Optional. If user message was a command , this parameter will be the command payload(if exist) */
    public string|null $command_payload = null;

    /**
     * Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     * @var messageEntity[]
     */
    public null|array $entities = null;

    /**
     * Optional. Options used for link preview generation for the message, if it is a text message and link preview
     * options were changed
     */
    public null|linkPreviewOptions $link_preview_options = null;

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

    /** Optional. Message is a forwarded story */
    public null|story $story = null;

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

    /** Optional. True, if the message media is covered by a spoiler animation */
    public null|bool $has_media_spoiler = null;

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
    public null|maybeInaccessibleMessage $pinned_message = null;

    /** Optional. Message is an invoice for a payment, information about the invoice. More about payments » */
    public null|invoice $invoice = null;

    /**
     * Optional. Message is a service message about a successful payment, information about the payment. More about
     * payments »
     */
    public null|successfulPayment $successful_payment = null;

    /** Optional. Service message: users were shared with the bot */
    public null|usersShared $users_shared = null;

    /**
     * Optional. Service message: a user was shared with the bot
     *
     * This is a legacy property, and could be removed in the future
     *
     * @deprecated use users_shared instead
     */
    public null|userShared $user_shared = null;

    /** Optional. Service message: a chat was shared with the bot */
    public null|chatShared $chat_shared = null;

    /** Optional. The domain name of the website on which the user has logged in. More about Telegram Login » */
    public null|string $connected_website = null;

    /**
     * Optional. Service message: the user allowed the bot to write messages after adding it to the attachment or
     * side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method
     * requestWriteAccess
     */
    public null|writeAccessAllowed $write_access_allowed = null;

    /** Optional. Telegram Passport data */
    public null|passportData $passport_data = null;

    /**
     * Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live
     * Location.
     */
    public null|proximityAlertTriggered $proximity_alert_triggered = null;

    /** Optional. Service message: user boosted the chat */
    public null|chatBoostAdded $boost_added = null;

    /** Optional. Service message: forum topic created */
    public null|forumTopicCreated $forum_topic_created = null;

    /** Optional. Service message: forum topic edited */
    public null|forumTopicEdited $forum_topic_edited = null;

    /** Optional. Service message: forum topic closed */
    public null|forumTopicClosed $forum_topic_closed = null;

    /** Optional. Service message: forum topic reopened */
    public null|forumTopicReopened $forum_topic_reopened = null;

    /** Optional. Service message: the 'General' forum topic hidden */
    public null|generalForumTopicHidden $general_forum_topic_hidden = null;

    /** Optional. Service message: the 'General' forum topic unhidden */
    public null|generalForumTopicUnhidden $general_forum_topic_unhidden = null;

    /** Optional. Service message: a scheduled giveaway was created */
    public null|giveawayCreated $giveaway_created = null;

    /** Optional. The message is a scheduled giveaway message */
    public null|giveaway $giveaway = null;

    /** Optional. A giveaway with public winners was completed */
    public null|giveawayWinners $giveaway_winners = null;

    /** Optional. Service message: a giveaway without public winners was completed */
    public null|giveawayCompleted $giveaway_completed = null;

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

    /**
     * Is it a command message or not
     *
     * @return bool
     */
    public function isCommand (): bool {
        return !empty($this->command);
    }

    /**
     * Is it forwarded or not
     *
     * @return bool
     */
    public function isForwarded (): bool {
        return $this->forward_origin !== null;
    }

    /**
     * Is it replied or not
     *
     * @return bool
     */
    public function isReplied (): bool {
        return !empty($this->reply_to_message);
    }

    /**
     * Is user admin of the chat or not
     *
     * Only in not private chat
     *
     * @return bool
     */
    public function isAdmin (): bool {
        return $this->chat->getMember($this->from->id)->status === chatMemberStatus::ADMINISTRATOR;
    }

    /**
     * Is user owner of the chat or not
     *
     * Only in not private chat
     *
     * @return bool
     */
    public function isOwner (): bool {
        return $this->chat->getMember($this->from->id)->status === chatMemberStatus::CREATOR;
    }

    /**
     * Ban member from this chat
     *
     * return false in private chats
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function banMember(bool $answer = null): responseError|bool {
        if ($this->chat->isPrivate()) {
            return false;
        }
        return telegram::banChatMember($this->chat->id, $this->from->id, answer: $answer);
    }

    /**
     * kick member from this chat
     *
     * return false in private chats
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function kickMember(bool $answer = null): responseError|bool {
        if ($this->chat->isPrivate()) {
            return false;
        }
        return telegram::unbanChatMember($this->chat->id, $this->from->id, answer: $answer);
    }

    /**
     * Delete this message
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function delete (bool $answer = null): responseError|bool {
        return telegram::deleteMessage($this->chat->id,$this->id, answer: $answer);
    }

    /**
     * Pin this message
     *
     * @param bool|null $answer
     *
     * @return responseError|bool
     */
    public function pinChatMessage (bool $answer = null): responseError|bool {
        return telegram::deleteMessage($this->chat->id,$this->id, answer: $answer);
    }

    /**
     * Edit message text(Only for bot messages or channel messages)
     *
     * @param string    $text
     * @param bool|null $answer
     *
     * @return message|responseError|bool
     */
    public function editText (string $text, bool $answer = null): message|responseError|bool {
        return telegram::editMessageText($text,  $this->chat->id, $this->message_id, answer: $answer);
    }

    /**
     * Copy this message(Anonymous forward)
     *
     * @param int|string $chat_id
     * @param bool|null  $answer
     *
     * @return messageId|responseError
     */
    public function copy (int|string $chat_id, bool $answer = null): messageId|responseError {
        return telegram::copyMessage($chat_id, answer: $answer);
    }

    /**
     * Forward this message
     *
     * @param int|string $chat_id
     * @param bool|null  $answer
     *
     * @return message|responseError
     */
    public function forward (int|string $chat_id, bool $answer = null): message|responseError {
        return telegram::forwardMessage($chat_id, answer: $answer);
    }
}
