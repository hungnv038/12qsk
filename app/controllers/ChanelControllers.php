<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/2/15
 * Time: 22:30
 */

class ChanelControllers extends BaseController {
    public function add()
    {
        try {
            $name=InputHelper::getInput('name',true);

            $chanel=Chanel::getInstance();
            if($chanel->isValidName($name)) {
                // exist name before ==> throw error
                throw new APIException("Chanel name already exist",APIException::ERRORCODE_DONE_ALREADY);
            }

            $chanel->insert(array('name'=>$name));

            return ResponseBuilder::success();

        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }

    }
    public function get($id) {
        try {
            $device_id=Device::getInstance()->authentication();
            if(!Chanel::getInstance()->isValid($id)) {
                throw new APIException("CHANEL ID INVALID",APIException::ERRORCODE_INVALID_INPUT);
            }
            $limit=InputHelper::getInput('limit',false,10);
            $since=InputHelper::getInput('since',false,time());
            $response=Chanel::getInstance()->get($id,$since,$limit,$device_id);

            return ResponseBuilder::success($response);

        }catch (Exception $e) {
            return ResponseBuilder::error($e);
        }
    }

    public function follow($id) {
        try {
            $device_id=Device::getInstance()->authentication();

            if(!Chanel::getInstance()->isValid($id)) {
                throw new APIException("CHANEL ID INVALID",APIException::ERRORCODE_INVALID_INPUT);
            }

            Device_Chanel::getInstance()->follow($device_id,$id);

            return ResponseBuilder::success();
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function unFollow($id) {
        try {
            $device_id=Device::getInstance()->authentication();

            if(!Chanel::getInstance()->isValid($id)) {
                throw new APIException("CHANEL ID INVALID",APIException::ERRORCODE_INVALID_INPUT);
            }

            Device_Chanel::getInstance()->unFollow($device_id,$id);

            return ResponseBuilder::success();
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function getList($chanel_id) {
        try {
            $device_id=Device::getInstance()->authentication();
            $limit=InputHelper::getInput('limit',false,3);
            $number_view=InputHelper::getInput('number_views',false,0);
            $loaded_ids=InputHelper::getInput("loadeds",false,array());

            if(!is_array($loaded_ids)) {
                $loaded_ids = preg_split("/,/", $loaded_ids);
            }
            $chanel=Chanel::getInstance();

            $list=$chanel->getList($device_id,$limit,$loaded_ids,$number_view);

            return ResponseBuilder::success(array('id'=>$device_id,'chanels'=>$list));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
} 