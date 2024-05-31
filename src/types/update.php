<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an incoming update.At most one of the optional parameters can be present in any given
 * update.
 */
class update extends types {
    /** Keep all properties which has sub properties */
    private const subs = [
        'message' => 'BPT\types\message',
        'edited_message' => 'BPT\types\message',
        'channel_post' => 'BPT\types\message',
        'edited_channel_post' => 'BPT\types\message',
        'business_connection' => 'BPT\types\businessConnection',
        'business_message' => 'BPT\types\message',
        'edited_business_message' => 'BPT\types\message',
        'deleted_business_messages' => 'BPT\types\businessMessagesDeleted',
        'message_reaction' => 'BPT\types\messageReactionUpdated',
        'message_reaction_count' => 'BPT\types\messageReactionCountUpdated',
        'inline_query' => 'BPT\types\inlineQuery',
        'chosen_inline_result' => 'BPT\types\chosenInlineResult',
        'callback_query' => 'BPT\types\callbackQuery',
        'shipping_query' => 'BPT\types\shippingQuery',
        'pre_checkout_query' => 'BPT\types\preCheckoutQuery',
        'poll' => 'BPT\types\poll',
        'poll_answer' => 'BPT\types\pollAnswer',
        'my_chat_member' => 'BPT\types\chatMemberUpdated',
        'chat_member' => 'BPT\types\chatMemberUpdated',
        'chat_join_request' => 'BPT\types\chatJoinRequest',
        'chat_boost' => 'BPT\types\chatBoostUpdated',
        'removed_chat_boost' => 'BPT\types\chatBoostRemoved',
    ];

    /**
     * The update's unique identifier. Update identifiers start from a certain positive number and increase
     * sequentially. This identifier becomes especially handy if you're using webhooks, since it allows you to ignore
     * repeated updates or to restore the correct update sequence, should they get out of order. If there are no new
     * updates for at least a week, then identifier of the next update will be chosen randomly instead of
     * sequentially.
     */
    public int $id;

    /**
     * The update's unique identifier. Update identifiers start from a certain positive number and increase
     * sequentially. This identifier becomes especially handy if you're using webhooks, since it allows you to ignore
     * repeated updates or to restore the correct update sequence, should they get out of order. If there are no new
     * updates for at least a week, then identifier of the next update will be chosen randomly instead of
     * sequentially.
     */
    public int $update_id;

    /** Optional. New incoming message of any kind - text, photo, sticker, etc. */
    public null|message $message = null;

    /**
     * Optional. New version of a message that is known to the bot and was edited. This update may at times be
     * triggered by changes to message fields that are either unavailable or not actively used by your bot.
     */
    public null|message $edited_message = null;

    /** Optional. New incoming channel post of any kind - text, photo, sticker, etc. */
    public null|message $channel_post = null;

    /**
     * Optional. New version of a channel post that is known to the bot and was edited. This update may at times be
     * triggered by changes to message fields that are either unavailable or not actively used by your bot.
     */
    public null|message $edited_channel_post = null;

    /**
     * Optional. The bot was connected to or disconnected from a business account, or a user edited an existing
     * connection with the bot
     */
    public null|businessConnection $business_connection = null;

    /** Optional. New message from a connected business account */
    public null|message $business_message = null;

    /** Optional. New version of a message from a connected business account */
    public null|message $edited_business_message = null;

    /** Optional. Messages were deleted from a connected business account */
    public null|businessMessagesDeleted $deleted_business_messages = null;

    /**
     * Optional. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must
     * explicitly specify "message_reaction" in the list of allowed_updates to receive these updates. The update
     * isn't received for reactions set by bots.
     */
    public null|messageReactionUpdated $message_reaction = null;

    /**
     * Optional. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in
     * the chat and must explicitly specify "message_reaction_count" in the list of allowed_updates to receive these
     * updates. The updates are grouped and can be sent with delay up to a few minutes.
     */
    public null|messageReactionCountUpdated $message_reaction_count = null;

    /** Optional. New incoming inline query */
    public null|inlineQuery $inline_query = null;

    /**
     * Optional. The result of an inline query that was chosen by a user and sent to their chat partner. Please see
     * our documentation on the feedback collecting for details on how to enable these updates for your bot.
     */
    public null|chosenInlineResult $chosen_inline_result = null;

    /** Optional. New incoming callback query */
    public null|callbackQuery $callback_query = null;

    /** Optional. New incoming shipping query. Only for invoices with flexible price */
    public null|shippingQuery $shipping_query = null;

    /** Optional. New incoming pre-checkout query. Contains full information about checkout */
    public null|preCheckoutQuery $pre_checkout_query = null;

    /**
     * Optional. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by
     * the bot
     */
    public null|poll $poll = null;

    /**
     * Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were
     * sent by the bot itself.
     */
    public null|pollAnswer $poll_answer = null;

    /**
     * Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only
     * when the bot is blocked or unblocked by the user.
     */
    public null|chatMemberUpdated $my_chat_member = null;

    /**
     * Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must
     * explicitly specify "chat_member" in the list of allowed_updates to receive these updates.
     */
    public null|chatMemberUpdated $chat_member = null;

    /**
     * Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right
     * in the chat to receive these updates.
     */
    public null|chatJoinRequest $chat_join_request = null;

    /**
     * Optional. A chat boost was added or changed. The bot must be an administrator in the chat to receive these
     * updates.
     */
    public null|chatBoostUpdated $chat_boost = null;

    /**
     * Optional. A boost was removed from a chat. The bot must be an administrator in the chat to receive these
     * updates.
     */
    public null|chatBoostRemoved $removed_chat_boost = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
