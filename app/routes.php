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

Route::get('/', 'AuthController@index');


// HOME
Route::get('/home/videos/new','HomeController@getAddNewVideoView');
Route::post('/service/videos','MovieControllers@postNewVideo');

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

Route::get('/movies/search','MovieControllers@search');
Route::get('/movies/top','MovieControllers@tops');
Route::get('/movies/relative','MovieControllers@getRelatives');
//Route::get('/movies/{id}','MovieControllers@get');
Route::post('/movies/{id}/view','MovieControllers@view');
Route::post('/movies/{id}/like','MovieControllers@like');
Route::post('/movies','MovieControllers@add');

// LOGS
Route::post('/deletelogs','LogController@deleteLog');
Route::get('/logs','LogController@getLog');
Route::get('/apidocs','LogController@getApiDocs');
Route::get('/getApiDoc','LogController@getApiDoc');
Route::match(array('GET', 'POST'), '/setApiDoc','LogController@setApiDoc');

// CRON
Route::get('/cron/video/status','VideoUploadController@checkVideoStatus');
Route::get('/cron/video/download','VideoUploadController@download');
Route::get('/cron/video/upload','VideoUploadController@upload');
Route::get('/cron','BackgroundProcessController@cron');


// CHANELS MANAGEMENT
Route::get('/home/chanels','HomeController@getChanelView');
Route::get('/home/videos','HomeController@getVideoView');
// Test
Route::get('/test',function() {
    $db=DBConnection::write();
    $db->delete("delete from movie where id=?",array(3303125));
   // Movie::getInstance()->delete(array('id'=>3303125));
});
