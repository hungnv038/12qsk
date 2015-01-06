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
            if($chanel->isValid($name)) {
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

    }
    public function getList()
    {

    }
    public function follow($id) {

    }
} 