<?php

namespace BPT;
/**
 * lock class , Manage and handle lock files
 */
class lock {
    /**
     * Check lock is exist or not
     *
     * @param string $name
     *
     * @return bool
     */
    public static function exist(string $name): bool {
        return file_exists(realpath(settings::$name."$name.lock"));
    }

    /**
     * Set lock(create lock)
     *
     * @param string $name
     *
     * @return bool
     */
    public static function set(string $name): bool {
        return touch(settings::$name."$name.lock");
    }

    /**
     * Save data in lock file and make it local
     *
     * @param string $name
     * @param string $data
     *
     * @return bool|int
     */
    public static function save(string $name, string $data): bool|int {
        return file_put_contents(settings::$name."$name.lock", $data) && chmod(settings::$name."$name.lock",0640);
    }

    /**
     * Read data from lock
     *
     * @param string $name
     *
     * @return bool|string
     */
    public static function read(string $name): bool|string {
        return file_get_contents(realpath(settings::$name."$name.lock"));
    }

    /**
     * Get last modify time of lock
     *
     * @param string $name
     *
     * @return bool|int
     */
    public static function mtime(string $name): bool|int {
        return filemtime(realpath(settings::$name."$name.lock"));
    }

    /**
     * Delete lock
     *
     * @param string $name
     *
     * @return bool
     */
    public static function delete(string $name): bool {
        return unlink(realpath(settings::$name."$name.lock"));
    }
}