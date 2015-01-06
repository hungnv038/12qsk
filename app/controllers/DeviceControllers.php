<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/2/15
 * Time: 22:29
 */

class DeviceControllers extends BaseController{
    public function register()
    {
        try {
            $id=InputHelper::getInput('id',true);
            $device_name=InputHelper::getInput('device_name',false,'');
            $os_version=InputHelper::getInput('os_version',false,'');

            $device=Device::getInstance();
            if($device->isValid($id)) {
                // update
                $device->update(
                    array(
                        'device_name'=>$device_name,
                        'os_version'=>$os_version,
                        'updated_at'=>array('now()')
                    ),
                    array('id'=>$id));
            } else {
                // register new
                $device->insert(array(
                        'id'=>$id,
                        'created_at'=>array('now()'),
                        'updated_at'=>array('now()'),
                        'last_login'=>array('now()'),
                        'device_name'=>$device_name,
                        'os_version'=>$os_version)
                );
            }

            // return data to client
            $device_object=$device->getOneObjectByField(array('id'=>$id));
            if($device_object==null) {
                throw new APIException("DEVICE OBJECT NOT FOUND",APIException::ERRORCODE_NOTFOUND);
            } else {
                return ResponseBuilder::success($device_object);
            }
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
} 