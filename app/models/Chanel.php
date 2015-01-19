<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/6/15
 * Time: 18:01
 */

class Chanel extends ModelBase{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Chanel();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('chanel');
    }
    public function isValidName($name)
    {
        $sql="select count(*) as count from chanel where name=?";
        $result=DBConnection::read()->select($sql,array($name));

        if($result[0]->count>0) {
            return true;
        } else {
            return false;
        }
    }

    protected function createCacheObject($id, $params)
    {
        $new_object=array(
            'id'=>$id,
            'created_at'=>$this->getParamValue('created_at',$params,time()),
            'updated_at'=>$this->getParamValue('updated_at',$params,time()),
            'name'=>$this->getParamValue('name',$params,'')
        );
        return (object)$new_object;
    }

    public function getList($group_id,$device_id,$limit,$loaded_ids,$number_view) {
        $watched_chanels=$this->getWatchedList($group_id,$device_id,$number_view,$limit,$loaded_ids);

        $watched_ids=array();
        foreach($watched_chanels as $item) {
            $watched_ids[]=$item->id;
        }
        $group_movies=Movie::getInstance()->getNewestInChanels($watched_ids,Constants::NUMBER_NEWEST_ITEMS);

        $response=array();

        foreach ($watched_chanels as $item) {
            $response[]=$this->composeResponse($item,$group_movies[$item->id]);
        }

        return $response;

    }
    private function composeResponse($chanel,$movies) {
        // is_followed is bool type
        if(!is_array($movies)) {
            $movies=array($movies);
        }

        $chanel=(array)$chanel;
        $chanel['movies']=array();

        $movie_ids=array();
        foreach ($movies as $item) {
            $movie_ids[]=$item->id;
        }

        $movies=Movie::getInstance()->getObjectsByFields(array('id'=>$movie_ids));

        foreach ($movies as $movie) {
            $chanel['movies'][]=Movie::getInstance()->composeResponse($movie);
        }
        return (object)$chanel;
    }

    public function get($id,$since,$limit,$device_id) {
        $chanel=$this->getOneObjectByField(array('id'=>$id));
        if(!$chanel) {
            return null;
        }
        $is_followed=Device_Chanel::getInstance()->isFollowed($device_id,$id);
        $movies=Movie::getInstance()->getByChanelId($id,$since,$limit);
        return $this->composeResponse($chanel,$movies,$is_followed);
    }

    private function getWatchedList($group_id,$device_id,$view_number,$limit,$loaded_ids) {
        $sql="select chanel.*, count(device_movie_action.id) as count from chanel
        inner join movie on movie.chanel_id=chanel.id
        inner join device_movie_action on device_movie_action.movie_id=movie.id
        where device_movie_action.event=? and device_id=? and chanel.id not in ('".implode($loaded_ids,"','")."')
        and group_id=?
        group by chanel.id
        having count <=?
        order by count(device_movie_action.id) desc, updated_at desc
        limit ? offset 0";

        $result=DBConnection::read()->select($sql,array('view',$device_id,$group_id,$view_number,$limit));

        return $result;
    }
} 