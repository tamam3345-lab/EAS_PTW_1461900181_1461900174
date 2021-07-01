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
Route::get('home', 'HomeController@index');
Route::get('pesan/{id}', 'PesanController@index');
Route::post('pesan/{id}', 'PesanController@pesan');
Route::get('check-out', 'PesanController@check_out');
Route::delete('check-out/{id}', 'PesanController@delete');

Route::get('konfirmasi-check-out', 'PesanController@konfirmasi');

Route::get('profile', 'ProfileController@index');
Route::post('profile', 'ProfileController@update');

Route::get('history', 'HistoryController@index');
Route::get('history/{id}', 'HistoryController@detail');
Route::post('history/{id}', 'PesanController@transaksi');

Route::get('barang', 'BarangController@index')->name('barang');

Route::get('admin', 'AdminController@admin');
Route::get('admin-user', 'AdminController@user');
Route::get('admin-laporan', 'AdminController@laporan');
Route::get('/hapus/{id}', 'AdminController@hapus');
Route::get('/delete/{id}', 'AdminController@delete');
Route::get('/destroy/{id}', 'AdminController@destroy');
