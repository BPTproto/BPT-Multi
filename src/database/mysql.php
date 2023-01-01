<?php

namespace BPT\database;

use BPT\BPT;
use BPT\constants\chatMemberStatus;
use BPT\constants\chatType;
use BPT\constants\loggerTypes;
use BPT\exception\bptException;
use BPT\lock;
use BPT\logger;
use BPT\settings;
use BPT\tools;
use BPT\types\callbackQuery;
use BPT\types\chatMemberUpdated;
use BPT\types\inlineQuery;
use BPT\types\message;
use Generator;
use mysqli;
use mysqli_result;

class mysql {
    private static mysqli $connection;

    private static bool $auto_process = true;

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function init (): void {
        $host = settings::$db['host'] ?? 'localhost';
        $port = settings::$db['port'] ?? 3306;
        $user = settings::$db['user'] ?? settings::$db['username'] ?? 'root';
        $pass = settings::$db['pass'] ?? settings::$db['password'] ?? '';
        self::$auto_process = !isset(settings::$db['auto_process']) || (isset(settings::$db['auto_process']) && settings::$db['auto_process'] == true);
        $dbname = settings::$db['dbname'];
        self::$connection = new mysqli($host, $user, $pass, $dbname, $port);
        if (self::$connection->connect_errno) {
            logger::write('SQL connection has problem : ' . self::$connection->connect_error, loggerTypes::ERROR);
            throw new bptException('SQL_CONNECTION_PROBLEM');
        }
        if (self::$auto_process && !lock::exist('BPT-MYSQL')) {
            self::install();
        }
    }

    private static function install (): void {
        self::pureQuery("
CREATE TABLE `users`
(
    `id`           BIGINT(20) NOT NULL,
    `username`     VARCHAR(32) NULL DEFAULT NULL,
    `lang_code`    VARCHAR(3)  NULL DEFAULT NULL,
    `first_active` INT(11) NOT NULL DEFAULT '0',
    `last_active`  INT(11) NOT NULL DEFAULT '0',
    `referral`     BIGINT(20) NULL DEFAULT NULL,
    `blocked`      BOOLEAN     NOT NULL DEFAULT FALSE,
    `step`         VARCHAR(64) NOT NULL DEFAULT 'main',
    `value`        TEXT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;");
        lock::set('BPT-MYSQL');
    }

    /**
     * @internal Only for BPT self usage , Don't use it in your source!
     */
    public static function process (): void {
        if (self::$auto_process) {
            if (isset(BPT::$update->message)) {
                self::processMessage(BPT::$update->message);
            }
            elseif (isset(BPT::$update->edited_message)) {
                self::processMessage(BPT::$update->edited_message);
            }
            elseif (isset(BPT::$update->callback_query)) {
                self::processCallbackQuery(BPT::$update->callback_query);
            }
            elseif (isset(BPT::$update->inline_query)) {
                self::processInlineQuery(BPT::$update->inline_query);
            }
            elseif (isset(BPT::$update->my_chat_member)) {
                self::processMyChatMember(BPT::$update->my_chat_member);
            }
        }
    }

    private static function processMessage (message $update): void {
        $type = $update->chat->type;
        if ($type === chatType::PRIVATE) {
            $user_id = $update->from->id;
            $first_active = $last_active = time();
            $referral = null;
            $username = $update->from->username;
            $lang_code = $update->from->language_code;
            if (isset($update->commend) && isset($update->commend_payload) && $update->commend === 'start' && str_starts_with($update->commend_payload, 'ref_')) {
                if (tools::isShorted(substr($update->commend_payload, 4))) {
                    $referral = tools::shortDecode(substr($update->commend_payload, 4));
                }
            }
            self::query("INSERT INTO `users`(`id`, `username`, `lang_code`, `first_active`, `last_active`, `referral`) VALUES (?,?,?,?,?,?) on duplicate key update `last_active` = ?, `username` = ?", [
                $user_id,
                $username,
                $lang_code,
                $first_active,
                $last_active,
                $referral,
                $last_active,
                $username
            ]);
        }
    }

    private static function processCallbackQuery (callbackQuery $update): void {
        $type = $update->message->chat->type;
        if ($type === chatType::PRIVATE) {
            $user_id = $update->from->id;
            $last_active = time();
            $username = $update->from->username;
            self::update('users', ['last_active' => $last_active, 'username' => $username], ['id' => $user_id], 1);
        }
    }

    private static function processInlineQuery (inlineQuery $update): void {
        $type = $update->chat_type;
        if ($type === chatType::PRIVATE || $type === chatType::SENDER) {
            $user_id = $update->from->id;
            $last_active = time();
            $username = $update->from->username;
            self::update('users', ['last_active' => $last_active, 'username' => $username], ['id' => $user_id], 1);
        }
    }

    private static function processMyChatMember (chatMemberUpdated $update): void {
        $type = $update->chat->type;
        if ($type === chatType::PRIVATE) {
            if ($update->new_chat_member->status === chatMemberStatus::MEMBER) {
                self::update('users', ['blocked' => false], ['id' => $update->from->id], 1);
            }
            else {
                self::update('users', ['blocked' => true], ['id' => $update->from->id], 1);
            }
        }
    }

    /**
     * Get real mysqli connections
     *
     * @return mysqli
     */
    public static function getMysqli (): mysqli {
        return self::$connection;
    }

    /**
     * Get affected rows
     *
     * @return int|string
     */
    public static function affected_rows (): int|string {
        return self::$connection->affected_rows;
    }

    /**
     * Get inserted id
     *
     * @return int|string
     */
    public static function insert_id (): int|string {
        return self::$connection->insert_id;
    }

    /**
     * Escape string with real_escape_string of mysqli class
     *
     * @param string $text
     *
     * @return string
     */
    public static function escapeString (string $text): string {
        return self::$connection->real_escape_string($text);
    }

    /**
     * Run query as what is it
     *
     * The library doesn't do anything on it
     *
     * It's like calling mysqli->query();
     *
     * @param string $query
     *
     * @return mysqli_result|bool
     */
    public static function pureQuery (string $query): mysqli_result|bool {
        return self::$connection->query($query);
    }

    /**
     * Run query with safe execution
     *
     * Replace inputs with `?` in query to be replaced safely with $vars in order
     *
     * e.g. : mysql::query('select * from `users` where `id` = ? limit 1',[123456789]);
     *
     * e.g. : mysql::query('update `users` set `step` = ? where `id` = ? limit 1',['main',123456789]);
     *
     * @param string $query
     * @param array  $vars        default [] or empty
     * @param bool   $need_result set if you want result be returned, default : true
     *
     * @return mysqli_result|bool
     */
    public static function query (string $query, array $vars = [], bool $need_result = true): mysqli_result|bool {
        $prepare = self::$connection->prepare($query);
        $types = '';
        foreach ($vars as $var) {
            if (is_int($var)) {
                $types .= 'i';
            }
            elseif (is_double($var)) {
                $types .= 'd';
            }
            else {
                $types .= 's';
            }
        }
        if (!empty($types)) {
            $prepare->bind_param($types,...$vars);
        }
        if (!$prepare->execute()) {
            logger::write(loggerTypes::WARNING, $prepare->error);
            return false;
        }
        return $need_result ? $prepare->get_result() : true;
    }

    private static function makeArrayReady (string &$query, array $array, string $operator = ' AND '): array {
        $first = true;
        $values = [];
        foreach ($array as $name => $value) {
            if ($first) {
                $first = false;
            }
            else {
                $query .= $operator;
            }
            $query .= " `$name` = ?";
            $values[] = $value;
        }
        return $values;
    }

    private static function makeQueryReady (string &$query, array $where = null, int $count = null, int $offset = null): array {
        $values = [];
        if (!empty($where)) {
            $query .= " WHERE";
            $values = self::makeArrayReady($query, $where);
        }
        if (!empty($count)) {
            $query .= !empty($offset) ? " LIMIT $offset,$count" : " LIMIT $count";
        }
        elseif (!empty($offset)) {
            $query .= " OFFSET $offset";
        }
        return $values;
    }

    /**
     * Run delete query
     *
     * e.g. : `mysql::delete('users',['id'=>123456789],1);`
     *
     * @param string     $table  table name
     * @param array|null $where  Set your ifs default : null
     * @param int|null   $count  Set if you want to delete specific amount of row default : null
     * @param int|null   $offset Set if you want to delete rows after specific row default : null
     *
     * @return mysqli_result|bool
     */
    public static function delete (string $table, array $where = null, int $count = null, int $offset = null): mysqli_result|bool {
        $query = "DELETE FROM `$table`";
        $res = self::makeQueryReady($query, $where, $count, $offset);
        return self::query($query, $res, false);
    }

    /**
     * Run update query
     *
     * e.g. : mysql::update('users',['step'=>'panel'],['id'=>123456789],1);
     *
     * @param string     $table  table name
     * @param array      $modify Set the data's you want to modify
     * @param array|null $where  Set your ifs default : null
     * @param int|null   $count  Set if you want to update specific amount of row default : null
     * @param int|null   $offset Set if you want to update rows after specific row default : null
     *
     * @return mysqli_result|bool
     */
    public static function update (string $table, array $modify, array $where = null, int $count = null, int $offset = null): mysqli_result|bool {
        $query = "UPDATE `$table` SET";
        $values = self::makeArrayReady($query, $modify, ', ');
        $res = self::makeQueryReady($query, $where, $count, $offset);
        return self::query($query, array_merge($values, $res), false);
    }

    /**
     * Run insert query
     *
     * e.g. : `mysql::insert('users',['id','column1','column2','column3'],[123456789,'value1','value2','value3']);`
     *
     * @param string       $table   table name
     * @param string|array $columns sets columns that you want to fill
     * @param array|string $values  sets value that you want to set
     *
     * @return mysqli_result|bool
     */
    public static function insert (string $table, string|array $columns, array|string $values): mysqli_result|bool {
        $query = "INSERT INTO `$table`(";
        $query .= '`' . (is_string($columns) ? $columns : implode('`,`', $columns)) . '`) VALUES (';
        if (is_string($values)) $values = [$values];
        $query .= '?' . str_repeat(',?', count($values) - 1) . ')';
        return self::query($query, $values, false);
    }

    /**
     * Run select query
     *
     * e.g. : mysql::select('users','*',['id'=>123456789],1);
     *
     * e.g. : mysql::select('users',['step','referrals'],['id'=>123456789],1);
     *
     * @param string       $table   table name
     * @param array|string $columns sets column that you want to retrieve , set '*' to retrieve all , default : '*'
     * @param array|null   $where   Set your ifs default : null
     * @param int|null     $count   Set if you want to select specific amount of row default : null
     * @param int|null     $offset  Set if you want to select rows after specific row default : null
     *
     * @return mysqli_result|bool
     */
    public static function select (string $table, array|string $columns = '*', array $where = null, int $count = null, int $offset = null): mysqli_result|bool {
        $query = "SELECT ";
        if ($columns == '*') {
            $query .= "* ";
        }
        else {
            $query .= '`' . (is_string($columns) ? $columns : implode('`,`', $columns)) . '` ';
        }
        $query .= "FROM `$table`";
        $res = self::makeQueryReady($query, $where, $count, $offset);
        return self::query($query, $res);
    }

    /**
     * Same as mysql::select but return first result as array
     *
     * mysql::selectArray('users','*',['id'=>123456789]);
     *
     * @param string       $table   table name
     * @param array|string $columns sets column that you want to retrieve , set '*' to retrieve all , default : '*'
     * @param array|null   $where   Set your ifs default : null
     *
     * @return null|bool|array
     */
    public static function selectArray (string $table, array|string $columns = '*', array $where = null): bool|array|null {
        $res = self::select($table, $columns, $where, 1);
        if ($res) {
            return $res->fetch_assoc();
        }
        return $res;
    }

    /**
     * Same as mysql::select but return first result as object(stdClass)
     *
     * mysql::selectObject('users','*',['id'=>123456789]);
     *
     * @param string       $table   table name
     * @param array|string $columns sets column that you want to retrieve , set '*' to retrieve all , default : '*'
     * @param array|null   $where   Set your ifs default : null
     */
    public static function selectObject (string $table, array|string $columns = '*', array $where = null) {
        $res = self::select($table, $columns, $where, 1);
        if ($res) {
            return $res->fetch_object();
        }
        return $res;
    }

    /**
     * Same as mysql::select but return each row as generator
     *
     * e.g. : mysql::selectEach('users','*',['id'=>123456789],1);
     * e.g. : mysql::selectEach('users',['id']);
     *
     * @param string       $table   table name
     * @param array|string $columns sets column that you want to retrieve , set '*' to retrieve all , default : '*'
     * @param array|null   $where   Set your ifs default : null
     * @param int|null     $count   Set if you want to select specific amount of row default : null
     * @param int|null     $offset  Set if you want to select rows after specific row default : null
     *
     * @return bool|Generator
     */
    public static function selectEach (string $table, array|string $columns = '*', array $where = null, int $count = null, int $offset = null): bool|Generator {
        $res = self::select($table, $columns, $where, $count, $offset);
        if ($res) {
            while ($row = $res->fetch_assoc()) yield $row;
        }
        else return $res;
    }
}