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
            $limit=InputHelper::getInput('limit',false,Constants::NUMBER_NEWEST_ITEMS);

            $list=Chanel::getInstance()->getList($group_id,$device_id,$limit);

            return ResponseBuilder::success(array('group_id'=>intval($group_id),'chanels'=>$list));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function getList() {
        try {
            $device_id=Device::getInstance()->authentication();
            $limit=InputHelper::getInput('limit',false,Constants::NUMBER_NEWEST_ITEMS);

            $list=Group::getInstance()->getList($device_id,$limit);

            return ResponseBuilder::success(array('id'=>$device_id,'groups'=>$list));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }

} 