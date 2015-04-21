<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/31/15
 * Time: 14:40
 */

class Wifi extends ModelBase {

    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Wifi();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('wifi');
    }

}