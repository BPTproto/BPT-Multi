<?php

namespace BPT\database;

use Medoo\Medoo;

/**
 * @class Database
 */
class database {
    /**
     * @method Json_init
     */
    public function json_init () {
        (new jsondb())->init(handler::$dbname);
    }
}