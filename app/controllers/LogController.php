<?php

class LogController extends AdminController {
    
    /**
     * Author: Hieu Trieu
     * Get all system logs for checking error, info and statistic
     **/
	public function getLog() {
        // Get parameters
        $data['level']          = Input::get('level', '');
        $data['php_sapi_name']  = Input::get('php_sapi_name', '');
        $data['error_code']     = Input::get('error_code', '');
        $data['message']     = Input::get('message', '');
        $logs = DB::table('_logs');
        if($data['level'] != '') {
            $logs->where('level', '=', $data['level']);    
        }
        if($data['error_code'] != '') {
            $logs->where('error_code', '=', $data['error_code']);    
        }
        if($data['message'] != '') {
            $logs->where('message',  'LIKE', "%{$data['message']}%");    
        }
        if($data['php_sapi_name'] != '') {
            $logs->where('php_sapi_name',  'LIKE', "%{$data['php_sapi_name']}%");    
        }
        
        $logs = $logs->orderBy('created_at', 'DESC')->paginate(15);  
		$this->layout->content = View::make('admin.log.index', array('logs' => $logs, 'data' => $data));
        //return View::make('admin.log.index', array('logs' => $logs));
	}
    
    /**
     * Author: Hieu Trieu
     * Get all system logs for checking error, info and statistic
     **/
	public function deleteLog() {
        $runTime['start_time'] = microtime(true);
        DB::table('_logs')->truncate();
        Log::info("Delete all Logs", $runTime);
        return Redirect::to('/logs');
	}
    /**
     * REST APIs Documents
     */
    public function getApiDocs() {
        $ajax = Input::get('ajax', 0);
        $service = Input::get('service', 'picchat');
        $sql = "SELECT * FROM api_docs WHERE status = 1 AND service = '{$service}' GROUP BY name";
        $apis = DBConnection::read()->select($sql);
        if($ajax == 0) {
            $this->layout->content = View::make('admin.apidocs.index', array('apis' => $apis));
        } else {
            echo View::make('admin.apidocs.list', array('apis' => $apis));
            exit;
        }
    }
    public function getApiDoc() {
        $name = Input::get('name', '');
        $version = Input::get('version', '3.1');
        $service = Input::get('service', 'picchat');
        $sql = "SELECT * FROM api_docs WHERE name = '{$name}' AND service = '{$service}' AND version = '{$version}'";
        $api = DBConnection::read()->select($sql);
        $apiData = null;
        if(isset($api[0])) {
            $apiData = $api[0];
            $apiData->content = unserialize($apiData->content);
        }
        foreach(InputHelper::$ver_availables as $aVer) {
            $availablesVersions[$aVer] = $aVer;
        }
        echo View::make('admin.apidocs.detail', array('apiData' => $apiData, 'version' => $version, 'availablesVersions' => $availablesVersions));
        exit;
    }

    /**
     * Create, edit, clone
     */
    public function setApiDoc() {
        $model = null;
        $id = Input::get('id', 0);
        $alert = array('error' => -1, 'msg' => '');
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = Input::get('name', '');
            $method = Input::get('method', '');
            $version = '1.0';
            $service = 'picchat';
            $groupApi = Input::get('group_api', '');
            $contentData = Input::get('content', '');
            foreach($contentData['fields'] as $index => $field) {
                if($field['name'] == '') {
                    unset($contentData['fields'][$index]);
                }
            }
            $content = DB::connection()->getPdo()->quote(serialize($contentData));
            //debug(Input::get('content', ''), true);

            if($id == 0) {
                $sql = "INSERT INTO api_docs (name, method, group_api, content, version, service) VALUE ('{$name}', '{$method}', '{$groupApi}', {$content}, '{$version}', '{$service}')";
                $api = DBConnection::write()->insert($sql);
            } else {
                $sql = "UPDATE api_docs SET name='{$name}', method='{$method}', group_api='{$groupApi}', content={$content}, version='{$version}', service='{$service}' WHERE id=$id";
                $api = DBConnection::write()->update($sql);
            }
        } else {
            /**
             * Clone Record
             */
            $action = Input::get('action', '');
            $id = Input::get('id', 0);
            $newVersion = Input::get('new_version', '1.0');
            if($action == 'clone' && $id > 0) {
                $allowInsert = true;
                $sql = "SELECT name, method, content, version, service FROM api_docs WHERE id={$id}";
                $apis = DBConnection::read()->select($sql);
                $apiNew = isset($apis[0]) ? $apis[0] : null;

                if($apiNew != null) {
                    $sqlOld = "SELECT version FROM api_docs WHERE name = ? AND service = ?";
                    $apiOlds = DBConnection::read()->select($sqlOld, array($apiNew->name, $apiNew->service));
                    foreach($apiOlds as $apiCheck) {
                        if($apiCheck->version == $newVersion) {
                            $allowInsert = false;
                        }
                    }
                }

                if($apiNew != null && $allowInsert == true) {
                    $sql = "INSERT INTO api_docs (name, method, content, service, version) VALUE (?,?,?,?,?)";
                    DBConnection::read()->insert($sql, array($apiNew->name, $apiNew->method, $apiNew->content, $apiNew->service, $newVersion));
                    $alert = array('error' => 0, 'msg' => 'The clone successfully.');
                } else {
                    $alert = array('error' => 1, 'msg' => 'The clone failed.');
                }

            } elseif($action == 'delete' && $id > 0) {
                $sql = "DELETE FROM api_docs WHERE id = ?";
                $checkDelete = DBConnection::read()->delete($sql, array($id));
                if($checkDelete) {
                    $alert = array('error' => 0, 'msg' => 'Api deleted.');
                } else {
                    $alert = array('error' => 1, 'msg' => 'Delete failed.');
                }
            }

        }
        $sql = "SELECT * FROM api_docs WHERE status = 1 ORDER BY group_api, name";
        $apis = DBConnection::read()->select($sql);
        $sqlApi = "SELECT * FROM api_docs WHERE id = $id LIMIT 1";
        $api = DBConnection::read()->select($sqlApi);
        $model = isset($api[0]) ? $api[0] : null;
        if($model != null) {
            $model->content = unserialize($model->content);
        }
        //debug($model->content['fields']);
        echo View::make('admin.apidocs.edit', array('model' => $model, 'apis' => $apis, 'alert' => $alert, 'availablesVersions' => array('1.0')));
        exit;
    }
}
