<?php

namespace BPT\api;

class telegram extends request {
    public function __call (string $name, array $arguments) {
        if (!isset($arguments[1]) && isset($arguments[0]) && is_array($arguments[0])) {
            return request::$name(...$arguments[0]);
        }
        else {
            return request::$name(...$arguments);
        }
    }
}