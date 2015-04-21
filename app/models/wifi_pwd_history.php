<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/31/15
 * Time: 14:40
 */

class Wifi_pwd_history extends ModelBase{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Wifi_pwd_history();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('wifi_pwd_history');
    }
}