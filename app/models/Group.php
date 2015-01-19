<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/19/15
 * Time: 12:19
 */

class Group extends ModelBase{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Group();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('chanel_group');
    }
    public function getWatchedList($device_id,$view_number,$limit,$loaded_ids) {
        $sql="
        select chanel_group.*,
        unix_timestamp(chanel_group.created_at) as created_at,
        unix_timestamp(chanel_group.updated_at) as updated_at,
        count(device_movie_action.id) as cnt
        from chanel_group
        inner join chanel on chanel.group_id=chanel_group.id
        inner join movie on movie.chanel_id=chanel.id
        inner join device_movie_action on device_movie_action.movie_id=movie.id
        where device_movie_action.event=? and device_id=? and chanel_group.id not in ('".implode($loaded_ids,"','")."')
        group by chanel_group.id
        having cnt <=?
        order by count(device_movie_action.id) desc, updated_at desc
        limit ? offset 0
        ";
        $result=DBConnection::read()->select($sql,array('view',$device_id,$view_number,$limit));

        return $result;
    }
    public function getList($device_id,$number_view,$limit,$loaded_ids) {
        $watcheds=$this->getWatchedList($device_id,$number_view,$limit,$loaded_ids);

        $watched_ids=array();
        foreach($watcheds as $item) {
            $watched_ids[]=$item->id;
        }
        $group_movies=Movie::getInstance()->getNewestInGroups($watched_ids,Constants::NUMBER_NEWEST_ITEMS);

        $response=array();

        foreach ($watcheds as $item) {
            $response[]=$this->composeResponse($item,$group_movies[$item->id]);
        }

        return $response;

    }

    public function composeResponse($group,$movies) {
        $group=(array)$group;
        $group['movies']=array();

        $movie_ids=array();
        foreach ($movies as $item) {
            $movie_ids[]=$item->id;
        }

        $movies=Movie::getInstance()->getObjectsByFields(array('id'=>$movie_ids));

        foreach ($movies as $movie) {
            $movie_obj=Movie::getInstance()->composeResponse($movie);
            $group['movies'][]=$movie_obj;
        }
        return (object)$group;
    }
} 