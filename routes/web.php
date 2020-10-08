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
    Route::get('/detail/{item}', 'AdminStockListController@stockDetail');
    Route::post('/submit', 'AdminStockListController@submit');
    Route::get('/process', 'AdminStockListController@processRaw');
    Route::post('/submit_process', 'AdminStockListController@submitProcess');
    Route::get('/bom_detail/{name}', 'AdminStockListController@bomDetail');
    Route::post('/processqr', 'AdminStockListController@processQr');
    Route::post('/processqr/company/{company?}', 'AdminStockListController@processQr');
    Route::get('/select_company', 'AdminStockListController@selectCompany');
});

Route::prefix('admin/trouble_report')->group(function (){
    Route::get('/', 'AdminTroubleReportController@index');
    Route::get('/{name}', 'AdminTroubleReportController@detail');
    Route::post('/{name}', 'AdminTroubleReportController@detail');
});
