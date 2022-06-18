<?php

namespace BPT;

use BPT\api\request;
use BPT\types\update;
use stdClass;

class BPT{
    public update $update;


    public function __construct (array|stdClass $settings) {
        settings::init($settings);
    }

    public function __call (string $name, array $arguments) {
        if (!isset($arguments[1]) && is_array($arguments[0])) {
            request::$name(...$arguments[0]);
        }
        else {
            request::$name($arguments);
        }
    }
}
