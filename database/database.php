<?php
  
 //define namespace
 namespace BPT\database;
 
 //use classes
 use Medoo\Medoo;
 use BPT\Database\jsondb;
 
 
  /**
   * @class Database
   */
  class database
  {
        
   /**
   * @const types database
   */
   const TYPES = array('Mysqli','Medoo','Json');
 
   /**
    * @const Medoo database types
    */
   const Medoo_Types = array('mysql','mariadb','pgsql','sybase','oracle','mssql', 'sqlite');

    /**
     * @var type database
     */
    protected $type;
      
    /**
     * @var host for database
     * Default value localhost
     */
    protected $host;
       
    /**
      * @var username database username 
      */
    protected $username;
      
    /**
      * @var dbname database name
      */
    protected $dbname;
       
    /**
      * @var charset database
      */
    protected $charset;
        
    /**
      * @var password database password
      */
    protected $password;
       
    /**
      * @var connection database
      */
    protected $connect;
       
      /**
       * @method Json_init
       * Create a Json database
       */
      protected function Json_init()
      {
        $JsonDb = new JsonDb();
        $JsonDb->init($this->dbname);
      }
      
      
      
  }
