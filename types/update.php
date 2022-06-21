<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents an incoming update.At most one of the optional parameters can be present in any given
 * update.
 */
class update extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'message' => 'BPT\types\message',
        'edited_message' => 'BPT\types\message',
        'channel_post' => 'BPT\types\message',
        'edited_channel_post' => 'BPT\types\message',
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
    ];

    /**
     * The update's unique identifier. Update identifiers start from a certain positive number and increase
     * sequentially. This ID becomes especially handy if you're using webhooks, since it allows you to ignore
     * repeated updates or to restore the correct update sequence, should they get out of order. If there are no new
     * updates for at least a week, then identifier of the next update will be chosen randomly instead of
     * sequentially.
     */
    public int $update_id;

    /** Optional. New incoming message of any kind - text, photo, sticker, etc. */
    public message $message;

    /** Optional. New version of a message that is known to the bot and was edited */
    public message $edited_message;

    /** Optional. New incoming channel post of any kind - text, photo, sticker, etc. */
    public message $channel_post;

    /** Optional. New version of a channel post that is known to the bot and was edited */
    public message $edited_channel_post;

    /** Optional. New incoming inline query */
    public inlineQuery $inline_query;

    /**
     * Optional. The result of an inline query that was chosen by a user and sent to their chat partner. Please see
     * our documentation on the feedback collecting for details on how to enable these updates for your bot.
     */
    public chosenInlineResult $chosen_inline_result;

    /** Optional. New incoming callback query */
    public callbackQuery $callback_query;

    /** Optional. New incoming shipping query. Only for invoices with flexible price */
    public shippingQuery $shipping_query;

    /** Optional. New incoming pre-checkout query. Contains full information about checkout */
    public preCheckoutQuery $pre_checkout_query;

    /** Optional. New poll state. Bots receive only updates about stopped polls and polls, which are sent by the bot */
    public poll $poll;

    /**
     * Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were
     * sent by the bot itself.
     */
    public pollAnswer $poll_answer;

    /**
     * Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only
     * when the bot is blocked or unblocked by the user.
     */
    public chatMemberUpdated $my_chat_member;

    /**
     * Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must
     * explicitly specify “chat_member” in the list of allowed_updates to receive these updates.
     */
    public chatMemberUpdated $chat_member;

    /**
     * Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right
     * in the chat to receive these updates.
     */
    public chatJoinRequest $chat_join_request;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
