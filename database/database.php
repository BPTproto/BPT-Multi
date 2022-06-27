<?php
  
 namespace BPT\database;
 
 use Medoo\Medoo;
 use BPT\database\{handller,jsondb};
 
  /**
   * @class Database
   */
  class database
  {
       
      /**
       * @method Json_init

       */
      public function json_init()
      {
       (new jsondb())->init(handller::$dbname);
      }
      
   }