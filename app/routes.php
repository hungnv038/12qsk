<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

// DEVICES
Route::post('/devices','DeviceControllers@register');

//MOVIES GROUP

Route::get('/groups/{group_id}/chanels','GroupController@getChanels');
Route::get('/groups','GroupController@getList');

// MOVIES CHANEL

Route::post('/chanels/{id}/unfollow','ChanelControllers@unFollow');
Route::post('/chanels/{id}/follow','ChanelControllers@follow');
Route::post('/chanels','ChanelControllers@add');

Route::get('/chanels/{id}','ChanelControllers@get');

// MOVIES

Route::get('/movies/top','MovieControllers@tops');
Route::get('/movies/{id}','MovieControllers@get');
Route::post('/movies/{id}/view','MovieControllers@view');
Route::post('/movies/{id}/like','MovieControllers@like');
Route::post('/movies','MovieControllers@add');

// LOGS
Route::post('/deletelogs','LogController@deleteLog');
Route::get('/logs','LogController@getLog');
Route::get('/apidocs','LogController@getApiDocs');
Route::get('/getApiDoc','LogController@getApiDoc');
Route::match(array('GET', 'POST'), '/setApiDoc','LogController@setApiDoc');

// TEST
Route::get('/test/view', function() {
     for($i=1;$i<100;$i++) {
         $number_view=rand(1,20);

         for($j=0;$j<=$number_view;$j++) {
             try{
                 $movie_id="Id #".rand(1,500);
                 DBConnection::write()->insert("insert into device_movie_action (device_id,movie_id,event) VALUES (?,?,?)",
                     array(strval($i),$movie_id,'view'));
             } catch(Exception $e) {
                 continue;
             }
         }


     }
});

// TEST
Route::get('/test/device', function() {

    for($i=1;$i<100;$i++) {

        DBConnection::write()->insert("Insert into device (id,created_at,os_version,device_name) VALUES (?,now(),?,?)",
            array(strval($i),"android","GT9300"));
    }
});
Route::get('/test', function() {
    $offset=Input::get('index');
    $i=0;
    for($i=$offset;$i<$offset+200;$i++) {
        //$title="Title #".$i;
        $chanel_id=rand(1,12);
        DBConnection::write()->insert("Insert into movie (id,created_at,title,length,chanel_id) VALUES (?,now(),?,6,?)",
            array("Id #".$i,"Title #".$i,$chanel_id));
    }
});
