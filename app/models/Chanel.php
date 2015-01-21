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

    public function getList($group_id,$device_id,$limit) {
        $watched_chanels=$this->getWatchedList($group_id,$device_id);

        $chanel_ids=array();
        foreach($watched_chanels as $item) {
            $chanel_ids[]=$item->id;
        }

        $unwatched_chanels=$this->getUnWatchedList($group_id,$chanel_ids);

        foreach($unwatched_chanels as $item) {
            $chanel_ids[]=$item->id;
        }

        $chanel_movies=Movie::getInstance()->getNewestInChanels($chanel_ids,$limit);

        $chanels=array();
        $chanels=array_merge($watched_chanels,$unwatched_chanels);

        $response=array();

        foreach ($chanels as $item) {
            if(isset($chanel_movies[$item->id])) {
                $response[]=$this->composeResponse($item,$chanel_movies[$item->id]);
            }
        }

        return $response;

    }
    private function composeResponse($chanel,$movies) {
        // is_followed is bool type
        if(!is_array($movies)) {
            $movies=array($movies);
        }

        $chanel=(array)$chanel;

        if(!isset($chanel['number_view'])) {
            $chanel['number_view']=0;
        }

        $chanel['movies']=array();

        foreach ($movies as $movie) {
            $chanel['movies'][]=Movie::getInstance()->composeResponse($movie);
        }
        $chanel= (object)$chanel;

        $chanel->id=intval($chanel->id);
        $chanel->created_at=intval($chanel->created_at);
        $chanel->updated_at=intval($chanel->updated_at);
        $chanel->number_view=intval($chanel->number_view);

        return $chanel;
    }

    public function get($id,$since,$limit) {
        $chanel=$this->getOneObjectByField(array('id'=>$id));
        if(!$chanel) {
            return null;
        }
        $movies=Movie::getInstance()->getByChanelId($id,$since,$limit);
        return $this->composeResponse($chanel,$movies);
    }

    private function getUnWatchedList($group_id,$watched_ids) {
        $sql="select chanel.*,
        unix_timestamp(chanel.created_at) as created_at,
        unix_timestamp(chanel.updated_at) as updated_at
        from chanel
        where id not in ('".implode($watched_ids,"','")."') and group_id=?
        order by created_at desc";

        $result=DBConnection::read()->select($sql,array($group_id));
        return $result;
    }

    private function getWatchedList($group_id,$device_id) {
        $sql="select chanel.*,
        unix_timestamp(chanel.created_at) as created_at,
        unix_timestamp(chanel.updated_at) as updated_at,
        count(device_movie_action.id) as number_view
        from chanel
        inner join movie on movie.chanel_id=chanel.id
        inner join device_movie_action on device_movie_action.movie_id=movie.id
        where device_movie_action.event=? and device_id=?
        and group_id=?
        group by chanel.id
        order by count(device_movie_action.id) desc, updated_at desc";

        $result=DBConnection::read()->select($sql,array('view',$device_id,$group_id));

        return $result;
    }
} 