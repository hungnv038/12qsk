<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/2/15
 * Time: 22:29
 */

class MovieControllers extends BaseController{
    public function add()
    {
        try {
            $chanel_id  =InputHelper::getInput('chanel_id',true);
            $id         =InputHelper::getInput('id',true);
            $title      =InputHelper::getInput('title',true);
            $length     =InputHelper::getInput('length',true);

            $chanel=Chanel::getInstance()->getOneObjectByField(array('id'=>$chanel_id));
            if(!$chanel) {
                throw new APIException("Chanel Id Invalid",APIException::ERRORCODE_INVALID_INPUT);
            }

            if(strlen($title)==0) {
                throw new APIException("Movie title invalid",APIException::ERRORCODE_INVALID_INPUT);
            }

            if(Movie::getInstance()->isValid($id)) {
                // exist ==> update
                Movie::getInstance()->update(
                    array(
                        'updated_at'=>array('now()'),
                        'chanel_id' =>$chanel_id,
                        'title'     =>$title,
                        'length'    =>$length
                    ),
                    array('id'=>$id)
                );
            } else {
                // not exist ==> add new
                Movie::getInstance()->insert(
                    array(
                        'id'=>$id,
                        'created_at'=>array('now()'),
                        'updated_at'=>array('now()'),
                        'chanel_id' =>$chanel_id,
                        'title'     =>$title,
                        'length'    =>$length
                    )
                );
                // init movie counters

                Movie_Counter::getInstance()->inserts(
                    array('created_at','updated_at','movie_id','event','cnt'),
                    array(
                        array(
                            'created_at'=>array('now()'),
                            'updated_at'=>array('now()'),
                            'movie_id'=>$id,
                            'event'=>Constants::ACTION_LIKE,
                            'cnt'=>0
                        ),
                        array(
                            'created_at'=>array('now()'),
                            'updated_at'=>array('now()'),
                            'movie_id'=>$id,
                            'event'=>Constants::ACTION_VIEW,
                            'cnt'=>0
                        ))
                );
            }

            return ResponseBuilder::success();

        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function get($id)
    {
        try {
            Device::getInstance()->authentication();

            $movie=Movie::getInstance()->getOneObjectByField(array('id'=>$id));
            if(!$movie) {
                throw new APIException("Movie not found",APIException::ERRORCODE_NOTFOUND);
            }

            return ResponseBuilder::success($movie);
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }

    }
    public function view($id) {
        try {
            $this->movieActionPerform($id,Constants::ACTION_VIEW);

            return ResponseBuilder::success();

        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function like($id) {
        try {
            $this->movieActionPerform($id,Constants::ACTION_LIKE);

            return ResponseBuilder::success();

        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }

    private function movieActionPerform($movie_id,$action) {

        $device_id=Device::getInstance()->authentication();

        if(!Movie::getInstance()->isValid($movie_id)) {
            throw new APIException("MOVIE ID INVALID",APIException::ERRORCODE_INVALID_INPUT);
        }

        Device_Movie::getInstance()->registerEvent($device_id,$movie_id,$action);

        Movie_Counter::getInstance()->updateCount($movie_id,$action,1);
    }
    public function tops() {
        try {
            $device_id=Device::getInstance()->authentication();

            $movies=Movie::getInstance()->tops();

            return ResponseBuilder::success(array('id'=>$device_id,'movies'=>$movies));
        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
    public function getRelatives() {
        try {
            Device::getInstance()->authentication();
            $id=InputHelper::getInput('movie_id',true);
            $movie=Movie::getInstance()->getOneObjectByField(array('id'=>$id));
            if($movie==null) {
                return ResponseBuilder::success(array('movie_id'=>$id,'relatives'=>array()));
            }

            $relatives=Movie::getInstance()->getRelativeMovies($movie->chanel_id,$movie->created_at,Constants::NUMBER_RELATIVE_ITEMS);

            $movies=array();
            foreach ($relatives as $item) {
                $movies[]=Movie::getInstance()->composeResponse($item);
            }
            return ResponseBuilder::success(array('movie_id'=>$id,'relatives'=>$movies));

        } catch(Exception $e) {
            return ResponseBuilder::error($e);
        }
    }
} 