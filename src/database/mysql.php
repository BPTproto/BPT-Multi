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

    private static string $db_name = '';

    /**
     * If you want to use it in standalone mode , you MUST set `auto_process` to `false`
     */
    public static function init (string $host = 'localhost', string $username = 'root', string $password = '', string $dbname = '', bool $auto_process = null, int $port = 3306): void {
        $host = settings::$db['host'] ?? $host;
        $port = settings::$db['port'] ?? $port;
        $user = settings::$db['user'] ?? settings::$db['username'] ?? $username;
        $pass = settings::$db['pass'] ?? settings::$db['password'] ?? $password;
        self::$auto_process = $auto_process ?? (!isset(settings::$db['auto_process']) || (isset(settings::$db['auto_process']) && settings::$db['auto_process'] == true));
        $dbname = settings::$db['dbname'] ?? $dbname;
        self::$db_name = $dbname;
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
            if (isset($update->command) && isset($update->command_payload) && $update->command === 'start' && str_starts_with($update->command_payload, 'ref_')) {
                if (tools::isShorted(substr($update->command_payload, 4))) {
                    $referral = tools::shortDecode(substr($update->command_payload, 4));
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
     * same as affectedRows
     *
     * @return int|string
     */
    public static function affected_rows (): int|string {
        return self::$connection->affected_rows;
    }

    /**
     * Get affected rows
     *
     * same as affected_rows
     *
     * @return int|string
     */
    public static function affectedRows (): int|string {
        return self::$connection->affected_rows;
    }

    /**
     * Get inserted id
     *
     * same as insertId
     *
     * @return int|string
     */
    public static function insert_id (): int|string {
        return self::$connection->insert_id;
    }

    /**
     * Get inserted id
     *
     * same as insert_id
     *
     * @return int|string
     */
    public static function insertId (): int|string {
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
     * Get last error
     *
     * @return string
     */
    public static function error (): string {
        return self::$connection->error;
    }

    /**
     * Get last error code
     *
     * @return int
     */
    public static function errno (): int {
        return self::$connection->errno;
    }

    /**
     * set database charset
     *
     * @param string $charset
     *
     * @return bool
     */
    public static function setCharset (string $charset): bool {
        return self::$connection->set_charset($charset);
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
     * it will use `pureQuery` if `$vars` be empty
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
        if (empty($vars)) {
            return self::pureQuery($query);
        }
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
        $prepare->bind_param($types,...$vars);
        if (!$prepare->execute()) {
            logger::write(loggerTypes::WARNING, $prepare->error);
            return false;
        }
        return $need_result ? $prepare->get_result() : true;
    }

    private static function whereBuilder(string &$query, array $where = null): array {
        if (empty($where)) {
            return [];
        }

        $query .= " WHERE";
        $first = true;
        $values = [];

        foreach ($where as $name => $value) {
            if ($first) {
                $first = false;
            }
            else {
                $query .= ' AND';
            }

            if (empty($value)) {
                $query .= " `$name` = ?";
                $values[] = $value;
                continue;
            }

            $operator = substr($value,0,2);
            $operator_value = substr($value,2);
            switch ($operator) {
                case '>=':
                    $query .= " `$name` >= ?";
                    $value = $operator_value;
                    break;
                case '<=':
                    $query .= " `$name` <= ?";
                    $value = $operator_value;
                    break;
                case '> ':
                    $query .= " `$name` > ?";
                    $value = $operator_value;
                    break;
                case '< ':
                    $query .= " `$name` < ?";
                    $value = $operator_value;
                    break;
                case '% ':
                    $query .= " `$name` like ?";
                    $value = $operator_value;
                    break;
                case '!=':
                    $query .= " `$name` != ?";
                    $value = $operator_value;
                    break;
                default:
                    $query .= " `$name` = ?";
                    break;
            }

            $values[] = $value;
        }

        return $values;
    }

    private static function groupByBuilder(string &$query, string|array $group_by = []): void {
        if (empty($group_by)) {
            return;
        }
        if (is_string($group_by)) {
            $group_by = [$group_by];
        }
        $query .= ' GROUP BY `' . implode('`, `',$group_by) . '`';
    }

    private static function orderByBuilder(string &$query, string|array $order_by = []): void {
        if (empty($order_by)) {
            return;
        }
        if (is_string($order_by)) {
            $order_by = [$order_by => 'ASC'];
        }

        $query .= ' ORDER BY `';

        $first = true;
        foreach ($order_by as $key => $mode) {
            if ($first) {
                $first = false;
            }
            else {
                $query .= ', ';
            }
            if (is_numeric($key)) {
                $key = $mode;
                $mode = 'ASC';
            }
            $query .= "$key` $mode";
        }
    }

    private static function countBuilder(string &$query, int $count = null, int $offset = null): void {
        if (!empty($count)) {
            $query .= !empty($offset) ? " LIMIT $offset,$count" : " LIMIT $count";
        }
        elseif (!empty($offset)) {
            $query .= " OFFSET $offset";
        }
    }

    private static function updateBuilder(string &$query, array $modify): array {
        $first = true;
        $values = [];

        foreach ($modify as $name => $value) {
            if ($first) {
                $first = false;
            }
            else {
                $query .= ' ,';
            }

            if (empty($value)) {
                $query .= " `$name` = ?";
                $values[] = $value;
                continue;
            }

            $operator = substr($value,0,2);
            $operator_value = substr($value,2);
            switch ($operator) {
                case '+=':
                    $query .= " `$name` = `$name` + ?";
                    $value = $operator_value;
                    break;
                case '-=':
                    $query .= " `$name` = `$name` - ?";
                    $value = $operator_value;
                    break;
                case '*=':
                    $query .= " `$name` = `$name` * ?";
                    $value = $operator_value;
                    break;
                case '/=':
                    $query .= " `$name` = `$name` / ?";
                    $value = $operator_value;
                    break;
                case '%=':
                    $query .= " `$name` = `$name` % ?";
                    $value = $operator_value;
                    break;
                default:
                    $query .= " `$name` = ?";
                    break;
            }

            $values[] = $value;
        }

        return $values;
    }

    private static function insertBuilder(string &$query, string|array $columns, array|string $values): array {
        $query .= '(`' . (is_string($columns) ? $columns : implode('`,`', $columns)) . '`) VALUES (';
        if (is_string($values)) $values = [$values];
        $query .= '?' . str_repeat(',?', count($values) - 1) . ')';
        return $values;
    }

    private static function selectBuilder (string &$query, string|array $columns): void {
        if ($columns == '*') {
            $query .= " * ";
            return;
        }
        if (is_string($columns)) {
            $query .= " `$columns` ";
            return;
        }
        $query .= ' ';
        foreach ($columns as $key => $column) {
            if (is_array($column)) {
                $function = array_key_first($column);
                $column = $column[$function];
                $formatted = "`$column`";
                if ($column == '*') {
                    $formatted = '*';
                    $column = 'all';
                }
                $query .= strtoupper($function) . "($formatted) as `{$function}_$column`";
            }
            else {
                $query .= "`$column`";
            }

            if ($key != array_key_last($columns)) {
                $query .= ', ';
            }
        }
        $query .= ' ';
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
     * @return bool
     */
    public static function delete (string $table, array $where = null, int $count = null, int $offset = null): bool {
        $query = "DELETE FROM `$table`";
        $vars = self::whereBuilder($query, $where);
        return self::query($query, $vars, false);
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
     * @return bool
     */
    public static function update (string $table, array $modify, array $where = null, int $count = null, int $offset = null): bool {
        $query = "UPDATE `$table` SET";
        $modify_vars = self::updateBuilder($query, $modify);
        $where_vars = self::whereBuilder($query, $where);
        self::countBuilder($query, $count, $offset);
        return self::query($query, array_merge($modify_vars, $where_vars), false);
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
     * @return bool
     */
    public static function insert (string $table, string|array $columns, array|string $values): bool {
        $query = "INSERT INTO `$table`";
        $values = self::insertBuilder($query, $columns, $values);
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
     * @param array|string $group_by group result based on these columns
     * @param array|string $order_by order result based on these columns
     *
     * @return mysqli_result|bool
     */
    public static function select (string $table, array|string $columns = '*', array $where = null, int $count = null, int $offset = null, array|string $group_by = [], array|string $order_by = []): mysqli_result|bool {
        $query = "SELECT";
        self::selectBuilder($query, $columns);
        $query .= "FROM `$table`";
        $var = self::whereBuilder($query,$where);
        self::groupByBuilder($query, $group_by);
        self::orderByBuilder($query, $order_by);
        self::countBuilder($query,$count,$offset);
        return self::query($query, $var);
    }

    /**
     * Same as mysql::select but return first result as array
     *
     * mysql::selectArray('users','*',['id'=>123456789]);
     *
     * @param string       $table   table name
     * @param array|string $columns sets column that you want to retrieve , set '*' to retrieve all , default : '*'
     * @param array|null   $where   Set your ifs default : null
     * @param array|string $group_by group result based on these columns
     * @param array|string $order_by order result based on these columns
     *
     * @return null|bool|array
     */
    public static function selectArray (string $table, array|string $columns = '*', array $where = null, array|string $group_by = [], array|string $order_by = []): bool|array|null {
        $res = self::select($table, $columns, $where, 1, 0, $group_by, $order_by);
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
     * @param array|string $group_by group result based on these columns
     * @param array|string $order_by order result based on these columns
     */
    public static function selectObject (string $table, array|string $columns = '*', array $where = null, array|string $group_by = [], array|string $order_by = []) {
        $res = self::select($table, $columns, $where, 1, 0, $group_by, $order_by);
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
     * @param array|string $group_by group result based on these columns
     * @param array|string $order_by order result based on these columns
     *
     * @return bool|Generator
     */
    public static function selectEach (string $table, array|string $columns = '*', array $where = null, int $count = null, int $offset = null, array|string $group_by = [], array|string $order_by = []): bool|Generator {
        $res = self::select($table, $columns, $where, $count, $offset, $group_by, $order_by);
        if ($res) {
            while ($row = $res->fetch_assoc()) yield $row;
        }
        else return $res;
    }

    /**
     * get backup from database, you can get full backup or specific table backup
     *
     * @param array|null $wanted_tables set if you want specific table which exist
     * @param bool       $table_data set false if you only want the creation queries(no data)
     * @param bool       $save set false if you want to receive sql as string
     * @param string     $file_name file name for saving
     *
     * @return string if save is true , return file name otherwise return sql data
     */
    public static function backup (array $wanted_tables = null, bool $table_data = true, bool $save = true, string $file_name = ''): string {
        self::setCharset('utf8mb4');

        $tables = array_column(self::query('SHOW TABLES')->fetch_all(),0);
        if (!empty($wanted_tables)) {
            $tables = array_intersect($tables, $wanted_tables);
        }

        $sql = '';

        if (empty($tables)) {
            logger::write('No table founded for backup, if your database has table : check $wanted_tables argument', loggerTypes::WARNING);
        }
        foreach ($tables as $table) {
            $sql .= self::query("SHOW CREATE TABLE `$table`")->fetch_row()[1] . ";\n\n";
            if ($table_data) {
                $total_rows = self::query("SELECT COUNT(*) as `cnt` FROM `$table`")->fetch_object()->cnt;
                for ($i = 0; $i < $total_rows; $i = $i + 1000) {
                    $sql .= "INSERT INTO " . $table . " VALUES";
                    $result = self::select($table, '*' , null, 1000, $i);
                    $field_count = $result->field_count;
                    $affected_rows = self::affected_rows();
                    $counter = 1;
                    while ($row = $result->fetch_row()) {
                        $sql .= "\n(";
                        for ($column = 0; $column < $field_count; $column++) {
                            $row[$column] = str_replace("\n", "\\n", addslashes($row[$column]));
                            $sql .= !empty($row[$column]) ? '"' . $row[$column] . '"' : '""';
                            if ($column < $field_count - 1) {
                                $sql .= ',';
                            }
                        }
                        $sql .= ')' . ($counter == $affected_rows ? ';' : ',');
                        $counter++;
                    }
                }
                if ($total_rows > 0) {
                    $sql .= "\n\n";
                }
            }
            $sql .= "\n";
        }

        if (!$save) {
            return $sql;
        }

        if (empty($file_name)) {
            $file_name = self::$db_name . time() . '.sql';
        }
        file_put_contents($file_name, $sql);
        return $file_name;
    }
}