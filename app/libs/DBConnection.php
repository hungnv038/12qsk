<?php

class DBConnection
{
    private static $db = null;

    public static function write()
    {
        $service_app=InputHelper::getServiceName();
        if($service_app==Constants::APP_MOVIE_HOT) {
            self::$db = DB::connection("movie_hot");
            return self::$db;
        } elseif ($service_app==Constants::APP_WIFI_SHARING) {
            self::$db = DB::connection("wifi_sharing");
            return self::$db;
        } else {
            return null;
        }

    }

    public static function read()
    {
        $service_app=InputHelper::getServiceName();
        if($service_app==Constants::APP_MOVIE_HOT) {
            self::$db = DB::connection("movie_hot");
            return self::$db;
        } elseif ($service_app==Constants::APP_WIFI_SHARING) {
            self::$db = DB::connection("wifi_sharing");
            return self::$db;
        } else {
            return null;
        }
    }
}
