<?php

namespace BPT;

class lock {
    public static function exist(string $name): bool {
        return file_exists(settings::$name."$name.lock");
    }

    public static function set(string $name): bool {
        return touch(settings::$name."$name.lock");
    }

    public static function save(string $name, string $data): bool|int {
        return file_put_contents(settings::$name."$name.lock", $data) && chmod(settings::$name."$name.lock",0640);
    }

    public static function read(string $name): bool|string {
        return file_get_contents(settings::$name."$name.lock");
    }

    public static function mtime(string $name): bool|int {
        return filemtime(settings::$name."$name.lock");
    }

    public static function delete(string $name): bool {
        return unlink(settings::$name."$name.lock");
    }
}