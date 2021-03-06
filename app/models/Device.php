<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/6/15
 * Time: 17:03
 */

class Device extends ModelBase{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Device();
        }
        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct('device'); // TODO: Change the autogenerated stub
    }

    protected  function createCacheObject($id,$params)
    {
        $new_obj=array(
            'id'        =>$this->getParamValue('id',$params,''),
            'created_at'=>  $this->getParamValue('created_at',$params,time()),
            'updated_at'=>  $this->getParamValue('updated_at',$params,time()),
            'last_login'=>  $this->getParamValue('last_login',$params,time()),
            'os_version'=>  $this->getParamValue('os_version',$params,''),
            'device_name'=>$this->getParamValue('device_name',$params,'')
        );
        $new_obj=(object)$new_obj;
        return $new_obj;
    }
    public function authentication() {
        $device_id=InputHelper::getAccessToken();
        if(!$this->isValid($device_id)) {
            throw new APIException("INVALID ACCESS TOKEN",APIException::ERRORCODE_INVALID_TOKEN);
        }
        return $device_id;
    }
} 