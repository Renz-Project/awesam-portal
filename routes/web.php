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
Route::group(['middleware' => 'auth'], function () {
    
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


//transactions
Route::get('/transactions','TransactionController@index')->name('transactions');



//Users
Route::get('/users','UserController@index')->name('users');
Route::post('/new-user','UserController@store')->name('new-user');
Route::post('/edit-user/{id}','UserController@editUser')->name('edit-user');



Route::get('/clients','ClientController@index')->name('clients');


Route::get('/locations','LocationController@index')->name('locations');

});
