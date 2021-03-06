<?php

/**
 * Created by PhpStorm.
 * User: HUNG NGUYEN
 * Date: 4/7/14
 * Time: 10:34 PM
 */
class InputHelper
{
    private static $input;
    private static $access_token;
    private static $service_app;
    public static function setInputArray( $input ) {
        self::$input = $input;
    }

    public static $ver_availables=array('1.0');

    public static function exist($name) {
        return array_key_exists($name, self::$input);
    }

    public static function getInput($name, $require, $default_value = null)
    {
        if (array_key_exists($name, self::$input)) {
            return self::$input[$name];
        } else {
            if (!$require) {
                return $default_value;
            } else {
                throw new APIException("$name Invalid", APIException::ERRORCODE_LACK_PARAMETER);
            }
        }
    }

    public static function getAccessToken()
    {
        if ( self::$access_token ) return self::$access_token;

        $header = Request::header();

        if ( Input::has('accesstoken')) {
            return Input::get('accesstoken');
        }  elseif (array_key_exists('accesstoken', $header)) {
            return $header['accesstoken'][0];
        }  elseif (array_key_exists('accesstoken', self::$input)) {
            return self::$input['accesstoken'];
        } else {
            return null;
        }
    }
    public static function setAccessToken( $token ) {
        self::$access_token = $token;
    }
    public static function getFile($filename,$require=false)
    {
        if(Input::hasFile($filename)) {
            return Input::file($filename);
        } elseif($require) {
            throw new APIException("$filename not attachment", APIException::ERRORCODE_LACK_PARAMETER);
        } else {
            return null;
        }
    }
    public static function getAllInput()
    {
        return self::$input;
    }
    public static function getServiceName()
    {
        if ( self::$service_app ) return self::$service_app;

        $header = Request::header();

        if ( Input::has('serviceapp')) {
            return Input::get('serviceapp');
        }  elseif (array_key_exists('serviceapp', $header)) {
            return $header['serviceapp'][0];
        }  elseif (array_key_exists('serviceapp', self::$input)) {
            return self::$input['serviceapp'];
        } else {
            return Constants::APP_MOVIE_HOT;
        }
    }
}
