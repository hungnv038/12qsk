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
    public function getWatchedList($device_id) {
        $sql="
        select chanel_group.*,
        unix_timestamp(chanel_group.created_at) as created_at,
        unix_timestamp(chanel_group.updated_at) as updated_at,
        count(device_movie_action.id) as number_view
        from chanel_group
        inner join chanel on chanel.group_id=chanel_group.id
        inner join movie on movie.chanel_id=chanel.id
        inner join device_movie_action on device_movie_action.movie_id=movie.id
        where device_movie_action.event=? and device_id=?
        group by chanel_group.id
        order by count(device_movie_action.id) desc, updated_at desc
        ";
        $result=DBConnection::read()->select($sql,array('view',$device_id));

        return $result;
    }
    private function getUnWatchedList($watched_ids) {
        $sql="select chanel_group.*,
            unix_timestamp(chanel_group.created_at) as created_at,
            unix_timestamp(chanel_group.updated_at) as updated_at
            from chanel_group where id not in ('".implode($watched_ids,"','")."')";
        $result=DBConnection::read()->select($sql);

        return $result;
    }
    public function getList($device_id,$limit) {
        $groups=array();
        $watcheds=$this->getWatchedList($device_id);

        $group_ids=array();
        foreach($watcheds as $item) {
            $group_ids[]=$item->id;
        }

        $unwatcheds=$this->getUnWatchedList($group_ids);
        foreach($unwatcheds as $item) {
            $group_ids[]=$item->id;
        }
        $group_movies=Movie::getInstance()->getNewestInGroups($group_ids,$limit);

        $response=array();

        $groups=array_merge($groups,$watcheds,$unwatcheds);

        foreach ($groups as $item) {
            if(isset($group_movies[$item->id])) {
                $response[]=$this->composeResponse($item,$group_movies[$item->id]);
            }
        }

        return $response;

    }

    public function composeResponse($group,$movies) {
        $group=(array)$group;

        if(!isset($group['number_view'])) {
            $group['number_view']=0;
        }

        $group['movies']=array();

        foreach ($movies as $movie) {
            $movie_obj=Movie::getInstance()->composeResponse($movie);
            $group['movies'][]=$movie_obj;
        }
        return (object)$group;
    }
} 