<?php

class BaseController extends Controller {
    public  function __construct()
    {
        $input = Input::json()->all();
        if ( count($input) == 0 ) {
            $input = Input::all();
        }

        InputHelper::setInputArray( $input );

        sleep(100);
    }
}
