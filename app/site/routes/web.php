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

Route::get('/', 'HomeController@index')->name('home');

// Route::get('nico', 'NicoController@index')->name('nico');
Route::prefix('nico')->group(function() {
    Route::get('/', 'NicoController@index')->name('nico');
    Route::post('register', 'NicoController@registerMylist')->name('nico_register');
    Route::get('image/{id}', 'NicoController@getImage')->name('nico_image');
    Route::get('ranking', 'NicoController@ranking')->name('nico_ranking');
});
