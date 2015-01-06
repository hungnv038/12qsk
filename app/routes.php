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


// MOVIES CHANEL

Route::post('/chanels/{id}/follow','ChanelControllers@follow');
Route::post('/chanels','ChanelControllers@add');

Route::get('/chanels/{id}','ChanelControllers@get');
Route::get('/chanels','ChannelControllers@getList');

// MOVIES

Route::get('/movies/{id}','MovieControllers@get');
Route::post('/movies/{id}/viewed','MovieControllers@viewed');
Route::post('/movies/{id}/like','MovieControllers@like');
Route::post('/movies','MovieControllers@add');

// LOGS
Route::post('/deletelogs','LogController@deleteLog');
Route::get('/logs','LogController@getLog');
Route::get('/apidocs','LogController@getApiDocs');
Route::get('/getApiDoc','LogController@getApiDoc');
Route::match(array('GET', 'POST'), '/setApiDoc','LogController@setApiDoc');
