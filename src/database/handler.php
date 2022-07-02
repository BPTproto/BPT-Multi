<?php

namespace BPT\database;

/**
 *
 */
class handler {
    /**
     * @const types database
     */
    const TYPES = ['Mysqli', 'Medoo', 'Json'];
    /**
     * @const Medoo database types
     */
    const Medoo_Types = ['mysql', 'mariadb', 'pgsql', 'sybase', 'oracle', 'mssql', 'sqlite'];
    /**
     * @var type database
     */
    public static $type;
    /**
     * @var host for database
     * Default value localhost
     */
    public static $host;
    /**
     * @var username database username
     */
    public static $username;
    /**
     * @var dbname database name
     */
    public static $dbname;
    /**
     * @var charset database
     */
    public static $charset;
    /**
     * @var password database password
     */
    public static $password;
    /**
     * @var connection database
     */
    public static $connect;


    /**
     *
     */
    public function __construct (array $settings) {
        if (in_array($settings['type'], self::TYPES)) {
            if ($settings['type'] === 'Mysqli') {
                if (self::CheckParam($settings)) {
                    $db = new \Mysqlidb([
                        'host'     => self::$host,
                        'username' => self::$username,
                        'password' => self::$password,
                        'db'       => self::$dbname,
                        'charset'  => self::$charset
                    ]);
                    if ($db) {
                        self::$connect = $db;
                        print_r($db);
                    }
                    else {
                        print self::$username;
                        throw new \exception('a problem to connecting');
                    }
                }
                else {
                    throw new \exception('required parameters not found');
                }
            }
            if ($settings['type'] === 'Json') {
                if (isset($settings['dbname'])) {
                    self::$type = $settings['type'];
                    self::$dbname = $settings['dbname'];
                    (new database())->json_init();
                }
                else {
                    throw new \exception('parameter dbanme not found');
                }
            }
        }
        else {
            throw new \exception('parameter type not found');
        }
    }

    private static function CheckParam (array $array) {
        if (isset($array['username']) && isset($array['dbname']) && isset($array['password'])) {
            self::$host = $array['host'] ?? 'localhost';
            self::$username = $array['username'];
            self::$dbname = $array['dbname'];
            self::$password = $array['password'];
            self::$charset = $array['charset'] ?? 'utf8';
            return true;
        }
        else {
            return false;
        }
    }
}