<?php

use Illuminate\Http\Request;

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

Route::middleware(['jwt.verify'])->group(function(){

	//user
	// Route::get('user/{limit}/{offset}', "UserController@getAll");
	// Route::post('user/{limit}/{offset}', "UserController@find");
	// Route::delete('user/delete/{id}', "UserController@delete");
	// Route::post('user/ubah', "UserController@ubah");
	Route::get('user/auth', "UserController@getAuthenticatedUser");
	Route::get('user/{limit}/{offset}', "UserController@getAll");
	Route::post('user/{limit}/{offset}', "UserController@find");
	Route::delete('user/delete/{id}', "UserController@delete");
	Route::post('user/ubah', "UserController@ubah");
	Route::get('user/data', "UserController@index");

	//buku
	Route::post('/book/store', 'BookController@store');
	Route::put('/book/{id}', 'BookController@update');
	Route::get('/book/{limit}/{offset}', 'BookController@getAll');
	Route::get('/book/{id}', 'BookController@show');
	Route::post('/book/{limit}/{offset}', 'BookController@find');
	Route::post('/book/register', 'BookController@register');
	Route::delete('/book/delete/{id}', 'BookController@delete');
	Route::post('/book/ubah', 'BookController@ubah');

	//Peminjam
	Route::post('pinjam/{id}', "PinjamController@index");
	Route::post('kembali/{id}', "PinjamController@kembali");
	Route::get('pinjam/{limit}/{offset}', "PinjamController@getAll");

	//cek login
	Route::get('user/check' , "UserController@getAuthenticatedUser");

});
