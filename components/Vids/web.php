<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => LaravelLocalization::setlocale()], function()
{
	Route::post('/vids/setfilter/{fromtype}/{to?}', "VidController@setfilter");
	Route::get('/vids/{video}/prev', "VidController@prev");
	Route::get('/vids/{video}/next', "VidController@next");
	Route::get('/vids/{video}/gomain', "VidController@gomain");

	Route::resource("vids", "VidController");
});
