<?php

Route::group(['prefix' => LaravelLocalization::setlocale()], function()
{
	Route::get('/', function () {
		redirect()->route('vids.index');
	});


	Route::get('/login', 'LoginController@show')->name('login.show');
	Route::post('/login', 'LoginController@login')->name('login');
	Route::get('/login/refresh', 'LoginController@refresh')->name('login.refresh');

	Route::get('/logout', 'LoginController@logout')->name('login.logout')->middleware('auth:api');
});
