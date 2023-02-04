<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents one button of the reply keyboard. For simple text buttons String can be used instead of
 * this object to specify text of the button. Optional fields web_app, request_contact, request_location, and
 * request_poll are mutually exclusive.
 * @method self setText(string $value)
 * @method self setRequest_contact(bool $value)
 * @method self setRequest_location(bool $value)
 * @method self setRequest_poll(keyboardButtonPollType $value)
 * @method self setWeb_app(webAppInfo $value)
 */
class keyboardButton extends types {
    /** Keep all of properties which has sub properties */
    private const subs = [
        'request_user' => 'BPT\types\keyboardButtonRequestUser',
        'request_chat' => 'BPT\types\keyboardButtonRequestChat',
        'request_poll' => 'BPT\types\keyboardButtonPollType',
        'web_app' => 'BPT\types\webAppInfo'
    ];

    /**
     * Text of the button. If none of the optional fields are used, it will be sent as a message when the button is
     * pressed
     */
    public string $text;

    /**
     * Optional. If specified, pressing the button will open a list of suitable users. Tapping on any user will send
     * their identifier to the bot in a “user_shared” service message. Available in private chats only.
     */
    public keyboardButtonRequestUser $request_user;

    /**
     * Optional. If specified, pressing the button will open a list of suitable chats. Tapping on a chat will send
     * its identifier to the bot in a “chat_shared” service message. Available in private chats only.
     */
    public keyboardButtonRequestChat $request_chat;

    /**
     * Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in
     * private chats only.
     */
    public bool $request_contact;

    /**
     * Optional. If True, the user's current location will be sent when the button is pressed. Available in private
     * chats only.
     */
    public bool $request_location;

    /**
     * Optional. If specified, the user will be asked to create a poll and send it to the bot when the button is
     * pressed. Available in private chats only.
     */
    public keyboardButtonPollType $request_poll;

    /**
     * Optional. If specified, the described Web App will be launched when the button is pressed. The Web App will be
     * able to send a “web_app_data” service message. Available in private chats only.
     */
    public webAppInfo $web_app;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}
