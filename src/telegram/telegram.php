<?php

namespace BPT\telegram;

/**
 * telegram class , Adding normal method call to request class and a simple name for being easy to call
 */
class telegram extends request {
    public function __call (string $name, array $arguments) {
        return request::$name(...$arguments);
    }
}