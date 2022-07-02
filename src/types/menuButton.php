<?php

namespace BPT\types;

use stdClass;

/**
 * This object describes the bot's menu button in a private chat.
 * @method self setType(string $value)
 * @method self setText(string $value)
 * @method self setWeb_app(webAppInfo $value)
 */
class menuButton extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['web_app' => 'BPT\types\webAppInfo'];

    /** Type of the button , could be `commands`, `web_app`, `default` */
    public string $type;

    /** `web_app` only. Text on the button */
    public string $text;

    /**
     * `web_app` only. Description of the Web App that will be launched when the user presses the button. The Web App will be able to
     * send an arbitrary message on behalf of the user using the method answerWebAppQuery.
     */
    public webAppInfo $web_app;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}