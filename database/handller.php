<?php

  //define namespace
  namespace BPT\Database;
  
  //use class
  use BPT\Database\Database;
  
  /**
   * 
   */
  class Handller extends Database
  {
      
      /**
       * 
       */
      public function __construct(array $settings)
      {
        if(in_array($settings['type'],self::TYPES)){
              if($settings['type'] === 'Mysqli'){
                  if(self::CheckParam($settings)){
                 $db = new Mysqlidb([
                'host' => $this->host,
                'username' => $this->username,
                'password' => $this->password,
                'db' => $this->dbname,
                'charset' => $this->charset
                ]);
                if($db){
                  $this->connect = $db;
               } else {
                  throw new \exception('a problem to connecting');
                 }
               } else {
                  throw new \exception('required parameters not found');
               }
             }
              if($settings['type'] === 'Json' and isset($settings['dbname'])){
                $this->type = $settings['type'];
                $this->dbname = $settings['dbname'];
                parent::Json_init();
              } else {
                throw new \exception('parameter dbanme not found');
             }
          } else {
              throw new \exception('parameter type not found');
          }
      }
      
      private static function CheckParam(array $array){
         if(isset($array['username']) && isset($array['dbname']) && isset($array['password'])){
            $this->host = $array['host'] ?? 'localhost';
            $this->username = $array['username'];
            $this->dbname = $array['dbname'];
            $this->password = $array['password'];
            $this->charset = $array['charset'] ?? 'utf8mb4';
             return true;
         } else {
             return false;
         }
      }
  }