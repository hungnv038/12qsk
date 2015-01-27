<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/6/15
 * Time: 21:30
 */

class Movie extends ModelBase {

    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Movie();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('movie');
    }

    protected function createCacheObject($id, $params)
    {
        $new_object=array(
            'id'            =>$this->getParamValue('id',$params,''),
            'created_at'    =>$this->getParamValue('created_at',$params,time()),
            'updated_at'    =>$this->getParamValue('updated_at',$params,time()),
            'title'         =>$this->getParamValue('title',$params,''),
            'length'        =>$this->getParamValue('length',$params,0),
            'chanel_id'     =>$this->getParamValue('chanel_id',$params,-1),
            'number_view'   =>0,
            'number_like'   =>0
        );
        return (object)$new_object;
    }
    // update movie counter value
    public function updateCount($id,$action,$step) {
        $movie=$this->getOneObjectByField(array('id'=>$id));
        if($movie) {
            if($action==Constants::ACTION_VIEW) {
                $movie->number_view+=$step;

                $this->addToCache($movie->id,$movie);
            } elseif($action==Constants::ACTION_LIKE) {
                $movie->number_like+=$step;

                $this->addToCache($movie->id,$movie);
            }
        }
    }
    public function getByChanelId($chanel_id,$since,$limit) {
        $sql="select movie.id,title,length,chanel_id,
                unix_timestamp(movie.created_at) as created_at,
                unix_timestamp(movie.updated_at) as updated_at,
                cnt as number_view
              from movie
              left join movie_counter on movie.id=movie_counter.movie_id and event=?
              where chanel_id=? and unix_timestamp(movie.created_at) < ?
              order by movie.created_at DESC
              limit 0, ?";
        $result=DBConnection::read()->select($sql,array('view',$chanel_id,$since,$limit));

        return $result;
    }
    public function getNewestInChanels($chanel_ids,$limit) {
        if(!is_array($chanel_ids)) {
            $chanel_ids=array($chanel_ids);
        }
        if(count($chanel_ids)==0) return null;

        $sql=" select *
                from (
                    select movie.id,title,length,chanel_id,
                    unix_timestamp(movie.created_at) as created_at,
                    unix_timestamp(movie.updated_at) as updated_at,
                    @num := if(@chanel = chanel_id, @num+1, 1) as row_number,
                    @chanel := chanel_id as chanel,cnt as number_view
                    from movie
                    left join movie_counter on movie.id=movie_counter.movie_id and event=?
                    where chanel_id in ('".implode($chanel_ids,"','")."')
                    group by chanel_id,movie.created_at desc,id) as f
                where row_number<=?
                order by chanel_id,created_at desc,id";
        $result=DBConnection::read()->select($sql,array('view',$limit));

        $chanel_movies=array();
        foreach ($result as $item) {
            if(!array_key_exists($item->chanel_id,$chanel_movies)) {
                $chanel_movies[$item->chanel_id]=array();
            }
            $chanel_movies[$item->chanel_id][]=$item;
        }
        return $chanel_movies;
    }
    public function composeResponse($movie) {
        // input is object, not array
        if(isset($movie->row_number)) unset($movie->row_number);
        if(isset($movie->chanel)) unset($movie->chanel);
        if(isset($movie->group_id)) unset($movie->group_id);
        if(isset($movie->row_chanel)) unset($movie->row_chanel);
        $movie=(array)$movie;
        $movie['thumb']="http://img.youtube.com/vi/".$movie['id']."/default.jpg";

        $movie= (object)$movie;

        $movie->created_at=intval($movie->created_at);
        $movie->updated_at=intval($movie->updated_at);
        $movie->chanel_id=intval($movie->chanel_id);
        $movie->length=intval($movie->length);
        $movie->number_view=intval($movie->number_view);


        return $movie;
    }
    public function tops() {
        $sql="select id,title,length,chanel_id,
                unix_timestamp(movie.created_at) as created_at,
                unix_timestamp(movie.updated_at) as updated_at,
                cnt as number_view
                from movie
                left join movie_counter on movie.id=movie_counter.movie_id and event=?
                inner join top_movie on movie.id=top_movie.movie_id
                order by order DESC
                limit 0, ?";
        $result=DBConnection::write()->select($sql,array('view',Constants::NUMBER_TOP_ITEMS));

        $response=array();
        foreach ($result as $item) {
            $response[]=$this->composeResponse($item);
        }

        return $response;
    }
    public function getNewestInGroups($group_ids,$limit) {
        if(!is_array($group_ids)) {
            $group_ids=array($group_ids);
        }
        if(count($group_ids)==0) return null;

        $sql="
                select *,
                @num := if(@chanel = group_id, @num+1, 1) as row_number,
                @chanel := group_id as row_chanel
                from
                (select movie.id,movie.title,movie.length,movie.chanel_id,
                    unix_timestamp(movie.created_at) as created_at,
                    unix_timestamp(movie.updated_at) as updated_at,
                    chanel.group_id,cnt as number_view
                from movie
                left join movie_counter on movie.id=movie_counter.movie_id and event=?
                join chanel on chanel.id=movie.chanel_id
                where group_id in ('".implode($group_ids,"','")."')
                order by chanel.group_id, movie.created_at desc) as f
                group by group_id,created_at desc, id
                having row_number<=?
                order by group_id,created_at desc";
        $result=DBConnection::read()->select($sql,array('view',$limit));

        $group_movies=array();

        foreach ($result as $item) {
            if(!array_key_exists($item->group_id,$group_movies)) {
                $group_movies[$item->group_id]=array();
            }
            $group_movies[$item->group_id][]=$item;
        }
        return $group_movies;
    }
    public function getRelativeMovies($chanel_id,$since,$limit) {
        $movies=array();

        $newers=$this->getNewer($chanel_id,$since,$limit);
        $movies=array_merge($movies,$newers);

        if(count($newers)<$limit) {
            $olders=$this->getolder($chanel_id,$since,$limit-count($newers));
            $movies=array_merge($movies,$olders);
        }

        return $movies;
    }
    private function getNewer($chanel_id,$since,$limit) {
        $sql="select movie.id,title,length,chanel_id,
                unix_timestamp(movie.created_at) as created_at,
                unix_timestamp(movie.updated_at) as updated_at,
                cnt as number_view
                from movie
                left join movie_counter on movie.id=movie_counter.movie_id and event=?
                where chanel_id=? and unix_timestamp(movie.created_at) > ?
                order by movie.created_at
                limit 0, ?";
        $result=DBConnection::read()->select($sql,array('view',$chanel_id,$since,$limit));
        return $result;
    }
    private function getolder($chanel_id,$since,$limit) {
        $sql="select movie.id,title,length,chanel_id,
                unix_timestamp(movie.created_at) as created_at,
                unix_timestamp(movie.updated_at) as updated_at,
                cnt as number_view
                from movie
                left join movie_counter on movie.id=movie_counter.movie_id and event=?
                where chanel_id=? and unix_timestamp(movie.created_at) < ?
                order by movie.created_at DESC
                limit 0, ?";
        $result=DBConnection::read()->select($sql,array('view',$chanel_id,$since,$limit));
        return $result;
    }
    public function search($text,$limit,$offset) {
        $sql="select movie.id,title,length,chanel_id,
                unix_timestamp(movie.created_at) as created_at,
                unix_timestamp(movie.updated_at) as updated_at,
                cnt as number_view
                from movie
                left join movie_counter on movie.id=movie_counter.movie_id and event=?
                where title like '%".$text."%'
                limit ? offset ?";
        $result=DBConnection::read()->select($sql,array('view',$limit,$offset));

        return $result;
    }

} 