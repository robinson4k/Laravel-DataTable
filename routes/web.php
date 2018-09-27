<?php

Route::post('allusers', 'UserController@allusers')->name('allusers');
Route::resource('users', 'UserController');

Route::get('/', function () {
    return view('welcome');
});
