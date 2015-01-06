<?php

class AdminController extends Controller {

    protected $layout = 'admin.layouts.default';
    protected $user;

    public function __construct(){
        //$this->beforeFilter('auth', array('except' => 'admin/login'));
    }
    public function login() {
        $this->layout->content = View::make('admin.auth.login');
    }
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout() {
		if ( ! is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}

    //AbstractAjaxController.php
    public function toJson($response, $subId, $totalItem){
        $json = array('error' => false, 'totalItem' => $totalItem, 'sub_id' => $subId, 'html' => '');

        if($response instanceof Illuminate\View\View) {
            $json['html'] = $response->render();
        } elseif($response instanceof Exception) {
                $json['error'] = $response->getMessage();
        } else {
            $json['data'] = $response;
        }
        return Response::json($json);
    }
}
