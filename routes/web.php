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

Auth::routes();

Route::group(['middleware' => 'auth'], function(){

    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/store', 'HomeController@store')->name('store');
    Route::get('/exists', 'HomeController@isExists')->name('exists');
    Route::delete('/delete', 'HomeController@delete')->name('delete');

    Route::get('/gallery', 'HomeController@gallery')->name('gallery');
});