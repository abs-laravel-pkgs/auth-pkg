<?php

Route::group(['namespace' => 'Abs\AuthPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'auth-pkg'], function () {

	Route::get('/profile', 'ProfileController@profile')->name('profile');
	Route::post('/profile/update', 'ProfileController@updateProfile')->name('updateProfile');

});