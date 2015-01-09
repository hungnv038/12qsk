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

    public function getLists($device_id,$since,$limit) {
        $followed_ids=array();
        $chanel_ids=array();
        $chanels=array();
        $followeds=$this->getFolloweds($device_id);

        foreach ($followeds as $item) {
            $followed_ids[]=$item->id;
            $chanel_ids[]=$item->id;
            if($item->created_at < $since) {
                $chanels[]=$item;
                if(count($chanels)==$limit) {
                    break;
                }
            }
            $this->addToCache($item->id,$item,ModelBase::CALL_ACTION_UPDATE);
        }
        if(count($chanels)!=$limit) {
            // get more from unfollow chanels
            $unfollows=$this->getUnFollows($since,$limit-count($chanels),$followed_ids);

            foreach ($unfollows as $item) {
                $chanel_ids[]=$item->id;
                $chanels[]=$item;

                $this->addToCache($item->id,$item,ModelBase::CALL_ACTION_UPDATE);
            }
        }
        if(count($chanels)==0) {
            return array();
        }

        // format data
        $chanel_movies=Movie::getInstance()->getChanelMovies($chanel_ids,Constants::NUMBER_NEWEST_ITEMS);

        $response=array();
        foreach ($chanels as $chanel) {
            $is_followed=in_array($chanel->id,$followed_ids);
            $response[]=$this->composeResponse($chanel,$chanel_movies[$chanel->id],$is_followed);
        }
        return $response;
    }
    private function getFolloweds($device_id) {
        $sql="select chanel.id,chanel.name,
                unix_timestamp(device_chanel.created_at) as created_at,
                unix_timestamp(device_chanel.updated_at) as updated_at
                from chanel
                inner join device_chanel on chanel.id=device_chanel.chanel_id
                where device_chanel.device_id=?
                order by device_chanel.created_at desc";
        $result=DBConnection::read()->select($sql,array($device_id));
        return $result;
    }
    private function getUnFollows($since,$limit,$follow_ids) {
        $sql="select id,name,
                unix_timestamp(created_at) as created_at,
                unix_timestamp(updated_at) as updated_at
                from chanel
                where unix_timestamp(created_at) < ? and id not in ('".implode("','",$follow_ids)."')
                order by created_at desc
                limit 0,?";
        $result=DBConnection::read()->select($sql,array($since,$limit));
        return $result;
    }
    private function composeResponse($chanel,$movies,$is_followed) {
        // is_followed is bool type
        if(!is_array($movies)) {
            $movies=array($movies);
        }

        $item=(array)$chanel;
        $item['is_followed']=$is_followed==true?1:0;
        $item['movies']=array();
        foreach ($movies as $movie) {
            $item['movies'][]=Movie::getInstance()->composeResponse($movie);
        }
        return (object)$item;
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


} 