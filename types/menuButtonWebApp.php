<?php

namespace BPT\types;

use stdClass;

/**
 * Represents a menu button, which launches a Web App.
 */
class menuButtonWebApp extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['web_app' => 'BPT\types\webAppInfo'];

    /** Type of the button, must be web_app */
    public string $type;

    /** Text on the button */
    public string $text;

    /**
     * Description of the Web App that will be launched when the user presses the button. The Web App will be able to
     * send an arbitrary message on behalf of the user using the method answerWebAppQuery.
     */
    public webAppInfo $web_app;


    public function __construct(stdClass $update) {
        parent::__construct($update, self::subs);
    }
}
