<?php

class HomeController extends BaseController {
    public function getAddNewVideoView() {
        $group_chanels=Group::getInstance()->getGroupsChanels();
        return View::make('home.add_video',array('groups'=>$group_chanels));
    }
    public function getChanelView() {
        return View::make('home.chanel_index');
    }
    public function getVideoView() {

    }
}
