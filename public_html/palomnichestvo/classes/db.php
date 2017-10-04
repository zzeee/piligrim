<?php
/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 30.11.2016
 * Time: 22:23
 */

class db {
    static private $_instance = null;
    static private $_pdo = null;


    private function __construct(){}
    private function __clone(){}

    static function prepare($sq)
    {
        self::getInstance2();
        if (self::$_pdo != null) {
            $rt = self::$_pdo;
            try {
                $rm=$rt->prepare($sq);
            }
            catch(Exception $e) {echo("err");}
        }else echo('no connection nuqqll');
        return $rm;
    }

    static function query2($sq)
    {
        self::getInstance2();
       $rm=0;
        if (self::$_pdo != null) {
            $rt = self::$_pdo;
            try {
               $rm=$rt->query($sq);
            }
            catch(Exception $e) {echo("db err");}
        }else echo('no connection nuqqll');
        return $rm;
    }

    static function fetchOne($sq)
    {
        $rt=db::query2($sq);
        if ($rt) return $rt->fetch(PDO::FETCH_ASSOC);
        else return false;
    }

    static function lastInsertId()
    {
        return self::$_pdo->lastInsertId();
    }

    static function getInstance2() {
        if(self::$_pdo == null) {
            self::$_pdo = new pdo("mysql:dbname=elitsy;host=127.0.0.1;charset=utf8", "elitsy", "Tkbws987654!");
        }
        return self::$_pdo;
    }


}