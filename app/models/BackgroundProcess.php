<?php
/**
 * Created by PhpStorm.
 * User: hajime3333
 * Date: 5/31/14
 * Time: 3:49 AM
 */

class BackgroundProcess extends ModelBase{
    private static $bgp;
    const NUMBER_LIMIT_PROCESS = 6;

    public function __construct( ) {         
	    self::initialize();
        parent::__construct('process');
    }

    public static function initialize( ) {
        set_time_limit(30);
        if (Input::server('HTTP_HOST') != "localhost" ) {
            // only report error when running on real hot, not in localhost
            throw new APIException("Invalid Session", APIException::ERRORCODE_FORBIDDEN);
        }
    }

    public static function getInstance( ) {
        if ( self::$bgp == null ) {
            self::$bgp = new BackgroundProcess();
        }
        return self::$bgp;
    }

    public function process($process_id) {
        if( self::countActiveProcess() < self::NUMBER_LIMIT_PROCESS ) {
            if ($process_id > 0) {
                $r = DBConnection::write()->select(
                    "SELECT * FROM process WHERE id = ?", array($process_id)
                );
                if (isset($r[0]) && $r[0]->status == 'waiting') {
                    DBConnection::write()->update(
                        "UPDATE process SET status = 'processing', started_at = now() WHERE id = ?", array($process_id)
                    );
                    self::run($r[0]->process);
                    DBConnection::write()->delete(
                        "DELETE FROM process WHERE id = ?", array($process_id)
                    );
                }
            }
        }
    }
    public static function throwScheduledProcess( $command, $parameter, $scheduled_at) {

        $param_query = http_build_query( $parameter );
        $url = $command . "?" . $param_query;

        DBConnection::write()->insert(
            "INSERT INTO process (status, ip, process, priority, scheduled_at, created_at) VALUES ('waiting',?,?,?,?,now())",
            array(Input::server("SERVER_ADDR"), $url, 'hight', $scheduled_at)
        );
    }
    public static function throwProcess( $command, $parameter = array() ) {
        try {

            $param_query = http_build_query( $parameter );
            $url = $command . "?" . $param_query;
            DBConnection::write()->insert(
                "INSERT INTO process (status, ip, process, priority, scheduled_at, created_at)
                 VALUES ('waiting',?,?,?,now(),now())",
                array(Input::server("SERVER_ADDR"), $url, 'hight')
            );
            $id = DB::getPdo()->lastInsertId();
            //BackgroundProcess::getInstance()->process($id);
            return true;
        } catch ( Exception $e ) {
            return false;
        }
    }

    public static function throwMultipleProcesses($commands=array()) {
        try {

            if(count($commands)==0) return;

            if(array_key_exists('command',$commands)) {
                $pro_commands=$commands['command'];
            } else {
                return;
            }
            if(array_key_exists('parameter',$commands)) {
                $pro_parameters=$commands['parameter'];
            } else {
                return;
            }
            if(count($pro_commands)!=count($pro_parameters)) { return;}

            $process_values=array();
            $index=-1;
            foreach($pro_commands as $command) {
                $index++;
                $parameter=$pro_parameters[$index];

                $param_query = http_build_query( $parameter );
                $url = $command . "?" . $param_query;

                $item=array(
                    'status'        => 'waiting',
                    'ip'            => Input::server("SERVER_ADDR"),
                    'process'       => $url,
                    'priority'      => 'hight',
                    'scheduled_at'  => array('now()'),
                    'created_at'    => array('now()')
                );
                $process_values[]=$item;
            }

            $ids = BackgroundProcess::getInstance()->inserts(
                array(
                    'status','ip','process','priority','scheduled_at','created_at'
                ), $process_values);
            if(count($ids)>0 ) {
                BackgroundProcess::getInstance()->process( $ids[0] );
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     * Get count process is available in 2 minutes
     */
    public static function countActiveProcess() {
        $r = DBConnection::read()->select("
            SELECT count(*) as cnt
            FROM process
            WHERE status = 'processing' AND (CURRENT_TIMESTAMP-started_at) < 200"
        );
        return $r[0]->cnt;
    }

    public static function countRecords() {
        $r = DBConnection::read()->select("
            SELECT count(*) as cnt
            FROM process"
        );
        return $r[0]->cnt;
    }

    private static function run($command) {
        try {
            if (self::getPathBase() == null) {
                $process = "http://localhost" . $command;
            } else {
                $process = "http://localhost".self::getPathBase() . "/" . $command;
            }
            exec("wget -O- '" . $process . "' > /dev/null");
            //Test on window
            //exec('cmd /c start '.$process);
            /*if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $process);
                curl_exec($ch);
            }*/
        } catch (Exception $e) {
            Log::error("Process Error:".$e->getTraceAsString());
        }
    }

    /**
     * @return null
     */
    private static function getPathBase() {
        $url_component = parse_url(url("/"));
        if ( isset( $url_component['path']) ) {
            return $url_component['path'];
        }
        return null;
    }
    
    /**
     * Hieu Trieu 
     * Get batch process
     **/
    public static function getBatchProcess($limit) {
        $process = DBConnection::write()->select("
            SELECT *
            FROM process
            WHERE status='waiting' AND scheduled_at < now()
            ORDER BY FIELD(priority, 'hight', 'middle', 'slow'), id
            LIMIT {$limit}
        ");
        return $process;
    }

    /**
     * @param $process
     * @return int
     */
    public static function checkProcessExsist($process) {
       $result = DBConnection::read()->select("SELECT * FROM process WHERE process LIKE '%{$process}%'");
        if(count($result)>0) {
            return $result;
        }else
            return -1;
    }

    /**
     * @param $command
     * @param bool $removeVersion
     * @return string
     * @throws APIException
     */
    public static function renderProcess($command, $removeVersion = true) {
        if($removeVersion) {
            $parameter['version'] = InputHelper::getApiVersion();
        }

        $param_query = http_build_query( $parameter );
        $url = $command . "?" . $param_query;
        return $url;
    }
}
