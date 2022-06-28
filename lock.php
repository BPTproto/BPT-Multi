<?php

namespace BPT;

class lock {
    public static function exist(string $name): bool {
        return file_exists("$name.lock");
    }

    public static function set(string $name): bool {
        return touch("$name.lock");
    }

    public static function save(string $name, string $data): bool|int {
        return file_put_contents("$name.lock", $data);
    }

    public static function read(string $name): bool|string {
        return file_get_contents("$name.lock");
    }

    public static function mtime(string $name): bool|int {
        return filemtime("$name.lock");
    }

    public static function delete(string $name): bool {
        return unlink("$name.lock");
    }
}