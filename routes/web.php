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
Route::post('/new-transaction','TransactionController@store')->name('store-transaction');
Route::get('/transactions-report','TransactionController@report')->name('transactions-report');



//Users
Route::get('/users','UserController@index')->name('users');
Route::post('/new-user','UserController@store')->name('new-user');
Route::post('/edit-user/{id}','UserController@editUser')->name('edit-user');



Route::get('/clients','ClientController@index')->name('clients');
Route::post('client/upload-document/{id}','ClientController@upload')->name('upload');
Route::post('/new-client','ClientController@store')->name('new-client');
Route::get('/client/{id}','ClientController@view')->name('view-client');
Route::post('client/edit-client-information/{id}','ClientController@updateInformation')->name('edit-client');
Route::post('/client/update-location/{id}','ClientController@updateLocation')->name('update-location-client');
Route::post('/change-avatar/{id}','ClientController@changeAvatar')->name('change-avatar-client');

Route::get('/locations','LocationController@index')->name('locations');



Route::get('/products','ProductController@index')->name('products');
Route::post('/products/store','ProductController@store')->name('new-product');
Route::post('/products/update/{id}','ProductController@editProduct')->name('edit-product');

Route::get('/office-supplies','OfficeSupplyController@index')->name('products');
Route::post('/office-supplies/store','OfficeSupplyController@store')->name('new-product');
Route::post('/office-supplies/update/{id}','OfficeSupplyController@editProduct')->name('edit-product');

Route::get('/inventory','StockMovementController@index')->name('inventory');
Route::post('/new-stock','StockMovementController@store')->name('create-stock');

Route::get('/office-supplies/inventory','StockMovementOfficeController@index')->name('inventory');
Route::post('office-supplies/new-stock','StockMovementOfficeController@store')->name('create-stock');

Route::get('audit-trails','AuditTrailController@index')->name('audit');

});
