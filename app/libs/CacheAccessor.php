<?php
/**
 * Created by PhpStorm.
 * User: hung
 * Date: 9/12/14
 * Time: 4:48 PM
 */

class CacheAccessor {

    private static $instance;
    private $redis;

    private $available_keys=array(
        'chanel','device','movie');
    public static function getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new CacheAccessor();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->redis = Redis::connection();
    }

    // get value store on redis cache
    public function get($group,$key) {
        if(in_array($group,$this->available_keys)) {
            $cache_key=$group.":".$key;
            if($this->redis->exists($cache_key)) {
                $result= $this->redis->get($cache_key);
                return (object)json_decode($result);
            } else {
                return null;
            }
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    /**
     * set key - value to store on redis cache
     * @param $key
     * @param $value
     */
    public function set($group,$key, $value) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            $this->redis->set($cacheKey, json_encode($value,JSON_UNESCAPED_UNICODE));
        } else {
            throw new Exception("{$group} is not support");
        }

    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($group,$key) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            if($this->redis->exists($cacheKey)) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    /**
     * Get values from redis server
     * @param $key
     * @param $max_score
     * @param $min_score
     * @param $offset
     * @param $count
     * @return mixed
     */
    public function zRevRangebyScore($group,$key, $max_score, $min_score, $offset=-1, $count=-1) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            if($count==-1) {
                $result = $this->redis->zrevrangebyscore($cacheKey,
                    (string)$max_score, (string)$min_score, array());
            } else {
                $result = $this->redis->zrevrangebyscore($cacheKey,
                    (string)$max_score, (string)$min_score,
                    array('LIMIT' => array('OFFSET' => $offset, 'COUNT' => $count)));
            }
            return $result;
        } else {
            throw new Exception("{$group} is not support");
        }

    }

    /**
     * @param $key
     * @param $min_score
     * @param $max_score
     * @param $offset
     * @param $count
     * @return mixed
     */
    public function zRangebyScore($group,$key, $min_score, $max_score, $offset=-1, $count=-1) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            if($count==-1) {
                $result = $this->redis->zrangebyscore($cacheKey, $min_score, $max_score, array());
            } else {
                $result = $this->redis->zrangebyscore($cacheKey, $min_score, $max_score, array('LIMIT' => array('OFFSET' => $offset, 'COUNT' => $count)));
            }

            return $result;
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $data
     */
    public function zAdd($group,$key, $score, $value) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
                $this->redis->zadd($cacheKey, $score, $value);

        } else {
            throw new Exception("{$group} is not support");
        }

    }

    public function zrem($group, $key, $value) {
        if(in_array($group, $this->available_keys)) {
            $cacheKey = $group .":". $key;
            $this->redis->zrem($cacheKey, $value);

        } else {
            throw new Exception("{$group} is not support");
        }

    }

    public function zRemRangebyScore($group, $key, $min_score, $max_score) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            $this->redis->zremrangebyscore($cacheKey, $min_score, $max_score);
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    public function zUpdatebyScore($group,$key,$old_score,$new_score,$new_value) {
        if(in_array($group,$this->available_keys)) {
            $cacheKey = $group.":".$key;
            $this->redis->zremrangebyscore($cacheKey, $old_score, $old_score);
            $this->redis->zadd($cacheKey, $new_score, $new_value);
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    public function getMinScore($group,$key)
    {
        if (in_array($group, $this->available_keys)) {
            $cacheKey = $group . ":" . $key;
            $values = $this->redis->zrangebyscore($cacheKey, -PHP_INT_MAX, PHP_INT_MAX, array('LIMIT' => array('OFFSET' => 0, 'COUNT' => 1)));
            if (count($values) > 0) {
                $first_val = $values[0];

                return $this->redis->zScore($cacheKey, $first_val);
            } else {
                return -1;
            }
        } else {
            throw new Exception("{$group} is not support");
        }
    }

    public function zupdateByValue($group, $key, $valueOld, $valueNew, $scoreNew) {
        if(in_array($group, $this->available_keys)) {
            $cacheKey = $group .":". $key;

            $this->redis->zrem($cacheKey, $valueOld);
            $this->redis->zadd($cacheKey, $scoreNew, $valueNew);
        } else {
            throw new Exception("{$group} is not support");
        }
    }
}