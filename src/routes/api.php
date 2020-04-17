<?php
Route::group(['namespace' => 'Abs\AuthPkg\Api', 'middleware' => ['api']], function () {
	Route::group(['prefix' => 'auth-pkg/api'], function () {
		Route::group(['middleware' => ['auth:api']], function () {
			// Route::get('taxes/get', 'TaxController@getTaxes');
		});
	});
});