<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/19/15
 * Time: 12:12
 */

class GroupController extends BaseController{
    public function getChanels($group_id)
    {
        try {
            $device_id=Device::getInstance()->authentication();
            $limit=InputHelper::getInput('limit',false,3);
            $number_view=InputHelper::getInput('views',false,PHP_INT_MAX);
            $loaded_ids=InputHelper::getInput("loaded_ids",false,array());

            if(!is_array($loaded_ids)) {
                $loaded_ids = preg_split("/,/", $loaded_ids);
            }

            $list=Chanel::getInstance()->getList($group_id,$device_id,$limit,$loaded_ids,$number_view);

            return ResponseBuilder::success(array('id'=>$device_id,'chanels'=>$list));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function getList() {
        try {
            $device_id=Device::getInstance()->authentication();
            $limit=InputHelper::getInput('limit',false,3);
            $number_view=InputHelper::getInput('views',false,PHP_INT_MAX);
            $loaded_ids=InputHelper::getInput("loaded_ids",false,array());

            if(!is_array($loaded_ids)) {
                $loaded_ids = preg_split("/,/", $loaded_ids);
            }

            $list=Group::getInstance()->getList($device_id,$number_view,$limit,$loaded_ids);

            return ResponseBuilder::success(array('id'=>$device_id,'groups'=>$list));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }

} 