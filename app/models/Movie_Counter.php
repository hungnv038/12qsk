<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 1/6/15
 * Time: 23:18
 */

class Movie_Counter extends ModelBase{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance==null) {
            self::$instance=new Movie_Counter();
        }
        return self::$instance;
    }
    public function __construct()
    {
        parent::__construct('movie_counter'); // TODO: Change the autogenerated stub
    }

    public function insert($param)
    {
        return parent::insert($param); // TODO: Change the autogenerated stub
    }

    public function updateCount($movie_id,$action,$step) {
        $movie_counter=$this->getOneObjectByField(array('movie_id'=>$movie_id,'event'=>$action));
        if($movie_counter!=null) {
            $cnt=$movie_counter->cnt+$step;
            $this->update(array('cnt'=>$cnt),array('movie_id'=>$movie_id,'event'=>$action));
        } else {
            // insert new
            $this->insert(array(
                'created_at'=>array('now()'),
                'movie_id'=>$movie_id,
                'event'=>$action,
                'cnt'=>1
            ));
        }
        Movie::getInstance()->updateCount($movie_id,$action,$step);
    }

    public function inserts($fields = array(), $fieldValues = array())
    {
        return parent::inserts($fields, $fieldValues); // TODO: Change the autogenerated stub
    }

} 