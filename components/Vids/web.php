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

// THERE IS A BUG WITH action HELPER IN distributed-laravel
// NOT BEING ABLE TO SEARCH FOR THE CONTROLLER IN THE RIGHT NAMESPACE
// SO ALWAYS GIVE A NAME TO EVERY ROUTE AND USE THE route HELPER INSTEAD

Route::group(['prefix' => LaravelLocalization::setlocale()], function()
{
	Route::post('/vids/setfilter/{fromtype}/{to?}', "VidController@setfilter")->name('vids.setfilter');
	
	Route::get('/vids/{vid}/prev', "VidController@prev")->name('vids.prev');
	Route::get('/vids/{vid}/next', "VidController@next")->name('vids.next');
	Route::get('/vids/{vid}/gomain', "VidController@gomain")->name('vids.gomain');

	Route::post('/vids/{vid}/vote', "VidController@vote")->name('vids.vote')->middleware('auth:api');

	Route::resource("vids", "VidController");
});
