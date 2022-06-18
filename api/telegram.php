<?php

namespace BPT\api;

class telegram {
    public function __call (string $name, array $arguments) {
        if (!isset($arguments[1]) && is_array($arguments[0])) {
            request::$name(...$arguments[0]);
        }
        else {
            request::$name($arguments);
        }
    }

    public static function __callStatic (string $name, array $arguments) {
        if (!isset($arguments[1]) && is_array($arguments[0])) {
            request::$name(...$arguments[0]);
        }
        else {
            request::$name(...$arguments);
        }
    }
}