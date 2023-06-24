<?php

namespace BPT\tools;
use stdClass;

/**
 * Simple class for working with cpanel functions and staff
 */
class cpanel {
    private static string $cpanelUser;
    private static string $cpanelPassword;
    private static string $cpanelUrl;
    private static int $cpanelPort;

    /**
     * Use this method and fill all arguments with right value for using this class
     *
     * @param string $cpanelUser
     * @param string $cpanelPassword
     * @param string $cpanelUrl
     * @param int    $cpanelPort
     *
     * @return void
     */
    public static function init (string $cpanelUser, string $cpanelPassword, string $cpanelUrl = '127.0.0.1', int $cpanelPort = 2083): void {
        self::$cpanelUser = $cpanelUser;
        self::$cpanelPassword = $cpanelPassword;
        self::$cpanelUrl = $cpanelUrl;
        self::$cpanelPort = $cpanelPort;
    }

    private static function createCurl () {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = 'Authorization: Basic ' . base64_encode(self::$cpanelUser . ':' . self::$cpanelPassword) . "\n\r";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        return $curl;
    }

    private static function execute (string $query = ''): bool|stdClass|array {
        $curl = self::createCurl();
        curl_setopt($curl, CURLOPT_URL, 'https://' . self::$cpanelUrl . ':' . self::$cpanelPort . '/execute/' . $query);
        $result = curl_exec($curl);
        if (!$result) {
            error_log('curl_exec threw error `' . curl_error($curl) . "` for $query");
        }
        curl_close($curl);
        $result = json_decode($result);
        if (!$result->status) {
            return $result->errors;
        }
        return $result->data ?? true;
    }

    private static function executev2 (string $module, string $function, array $vars = []) {
        $curl = self::createCurl();
        $vars = array_merge([
            'cpanel_jsonapi_user'       => 'user',
            'cpanel_jsonapi_apiversion' => '2',
            'cpanel_jsonapi_module'     => $module,
            'cpanel_jsonapi_func'       => $function,
        ], $vars);
        curl_setopt($curl, CURLOPT_URL, 'https://' . self::$cpanelUrl . ':' . self::$cpanelPort . '/json-api/cpanel?' . http_build_query($vars));
        $result = curl_exec($curl);
        if (!$result) {
            error_log('curl_exec threw error `' . curl_error($curl) . '` for ' . json_encode($vars));
        }
        curl_close($curl);
        return $result;
    }

    /**
     * @param string $database
     * @param string $user
     * @param string $password
     * @param array  $privileges
     *
     * @return array
     */
    public static function mysqlWizard (string $database, string $user, string $password, array $privileges = []): array {
        $create_database = self::createMysqlDatabase($database);
        $create_user = self::createMysqlUser($user, $password);
        if (empty($privileges)) {
            $set_privileges = self::setMysqlPrivilegesAll($database, $user);
        }
        else {
            $set_privileges = self::setMysqlPrivileges($database, $user, $privileges);
        }
        return [
            'create_database' => $create_database,
            'create_user'     => $create_user,
            'set_privileges'  => $set_privileges,
        ];
    }

    /**
     * @param string $database
     *
     * @return bool|array|stdClass
     */
    public static function createMysqlDatabase (string $database): bool|array|stdClass {
        if (!str_starts_with($database, self::$cpanelUser)) {
            $database = self::$cpanelUser . '_' . $database;
        }
        return self::execute("Mysql/create_database?name=$database");
    }

    /**
     * @param string $database
     *
     * @return bool|array|stdClass
     */
    public static function deleteMysqlDatabase (string $database): bool|array|stdClass {
        if (!str_starts_with($database, self::$cpanelUser)) {
            $database = self::$cpanelUser . '_' . $database;
        }
        return self::execute("Mysql/delete_database?name=$database");
    }

    /**
     * @param string $user
     * @param string $password
     *
     * @return bool|array|stdClass
     */
    public static function createMysqlUser (string $user, string $password): bool|array|stdClass {
        if (!str_starts_with($user, self::$cpanelUser)) {
            $user = self::$cpanelUser . '_' . $user;
        }
        return self::execute("Mysql/create_user?name=$user&password=$password");
    }

    /**
     * @param string $user
     *
     * @return bool|array|stdClass
     */
    public static function deleteMysqlUser (string $user): bool|array|stdClass {
        if (!str_starts_with($user, self::$cpanelUser)) {
            $user = self::$cpanelUser . '_' . $user;
        }
        return self::execute("Mysql/delete_user?name=$user");
    }

    /**
     * @param string $database
     * @param string $user
     * @param array  $privileges
     *
     * @return bool|array|stdClass
     */
    public static function setMysqlPrivileges (string $database, string $user, array $privileges): bool|array|stdClass {
        if (!str_starts_with($database, self::$cpanelUser)) {
            $database = self::$cpanelUser . '_' . $database;
        }
        if (!str_starts_with($user, self::$cpanelUser)) {
            $user = self::$cpanelUser . '_' . $user;
        }
        $all_privileges = [
            'ALTER',
            'ALTER ROUTINE',
            'CREATE',
            'CREATE ROUTINE',
            'CREATE TEMPORARY TABLES',
            'CREATE VIEW',
            'DELETE',
            'DROP',
            'EVENT',
            'EXECUTE',
            'INDEX',
            'INSERT',
            'LOCK TABLES',
            'REFERENCES',
            'SELECT',
            'SHOW VIEW',
            'TRIGGER',
            'UPDATE',
        ];
        $privileges = array_intersect($all_privileges, $privileges);
        if (empty($privileges)) {
            return false;
        }
        $privileges = urlencode(implode(',', $privileges));
        return self::execute("Mysql/set_privileges_on_database?user=$user&database=$database&privileges=$privileges");
    }

    /**
     * @param string $database
     * @param string $user
     *
     * @return bool|array|stdClass
     */
    public static function setMysqlPrivilegesAll (string $database, string $user): bool|array|stdClass {
        if (!str_starts_with($database, self::$cpanelUser)) {
            $database = self::$cpanelUser . '_' . $database;
        }
        if (!str_starts_with($user, self::$cpanelUser)) {
            $user = self::$cpanelUser . '_' . $user;
        }
        return self::execute("Mysql/set_privileges_on_database?user=$user&database=$database&privileges=ALL");
    }

    /**
     * @param string $old_name
     * @param string $new_name
     *
     * @return bool|array|stdClass
     */
    public static function changeMysqlDatabaseName (string $old_name, string $new_name): bool|array|stdClass {
        if (!str_starts_with($old_name, self::$cpanelUser)) {
            $old_name = self::$cpanelUser . '_' . $old_name;
        }
        if (!str_starts_with($new_name, self::$cpanelUser)) {
            $new_name = self::$cpanelUser . '_' . $new_name;
        }
        return self::execute("Mysql/rename_database?oldname=$old_name&newname=$new_name");
    }

    /**
     * @param string $old_name
     * @param string $new_name
     *
     * @return bool|array|stdClass
     */
    public static function changeMysqlUserName (string $old_name, string $new_name): bool|array|stdClass {
        if (!str_starts_with($old_name, self::$cpanelUser)) {
            $old_name = self::$cpanelUser . '_' . $old_name;
        }
        if (!str_starts_with($new_name, self::$cpanelUser)) {
            $new_name = self::$cpanelUser . '_' . $new_name;
        }
        return self::execute("Mysql/rename_user?oldname=$old_name&newname=$new_name");
    }

    /**
     * @param string $database
     *
     * @return bool|array|stdClass
     */
    public static function dumpMysqlDatabaseSchema (string $database): bool|array|stdClass {
        if (!str_starts_with($database, self::$cpanelUser)) {
            $database = self::$cpanelUser . '_' . $database;
        }
        return self::execute("Mysql/dump_database_schema?dbname=$database");
    }

    /**
     * @return bool|array|stdClass
     */
    public static function mysqlDatabases (): bool|array|stdClass {
        return self::execute('Mysql/list_databases');
    }

    /**
     * @return bool|array|stdClass
     */
    public static function mysqlUsers (): bool|array|stdClass {
        return self::execute('Mysql/list_users');
    }
}