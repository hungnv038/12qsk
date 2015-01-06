<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 12/25/14
 * Time: 12:14
 */

class QueryHelper {
    public static function LogQueries()
    {
        if(Config::get("app.debug")) {
            // only write query log when running on debug mode
            $queries = DB::getQueryLog();
            Log::info(json_encode($queries));
        }
    }
} 