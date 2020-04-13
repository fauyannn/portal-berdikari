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

Route::get('/', function () {
    return redirect()->to(url('admin'));
    // return view('welcome');
});

Route::prefix('admin/stocklist')->group(function (){
    Route::get('/', 'AdminStockListController@index');
    Route::get('/company/{company?}', 'AdminStockListController@index');
    Route::post('/submit', 'AdminStockListController@submit');
    Route::post('/processqr', 'AdminStockListController@processQr');
    Route::post('/processqr/company/{company?}', 'AdminStockListController@processQr');
    Route::get('/select_company', 'AdminStockListController@selectCompany');
});
