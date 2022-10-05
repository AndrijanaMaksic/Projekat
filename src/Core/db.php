<?php

namespace Jewelry\Core;

use PDO;

class Db{
    private static $instance;

    private static function connect():PDO{
        $dbConfig = Config::getInstance()->get('db');
        return new PDO('mysql:host=127.0.0.1;dbname=jewelry',$dbConfig['user'],$dbConfig['password']);
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = self::connect();
        }
        return self::$instance;
    }
}