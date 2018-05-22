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
Route::post('oficios', 'OficioController@oficios')->name('oficios');
Route::get('prueba', 'OficioController@prueba')->name('prueba');
Route::get('getoficioah/{id}', 'ActasHechosController@getoficioah');
Route::post('getToken', 'OficioController@getToken')->name('getToken');
Route::post('saveOficio', 'OficioController@saveOficio')->name('saveOficio');
Route::post('intentos', 'OficioController@intentos')->name('intentos');