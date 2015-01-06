<?php

class DBConnection
{
    private static $db = null;

    public static function write()
    {
        self::$db = DB::connection("mysql");
        return self::$db;
    }

    public static function read()
    {
        if (self::$db) return self::$db;
        return DB::connection("mysql"); //Slave
    }
}
