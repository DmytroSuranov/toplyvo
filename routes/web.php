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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('cards', 'CardsController@index')->name('cards');
    Route::get('cards/add', 'CardsController@create')->name('add-card');
    Route::post('cards/store', 'CardsController@store')->name('store-card');
    Route::get('cards/{card}/delete', 'CardsController@delete')->name('delete-card');

    Route::get('cards/cash', 'CardsController@cashServicePage')->name('cash-service');
    Route::post('cards/cash', 'CardsController@changeCardValue')->name('cash-service-execute');

    Route::post('cards/transfer', 'CardsController@transfer')->name('transfer-money');
    Route::post('cards/send', 'CardsController@send')->name('send-money');

    Route::post('cards/transfer/get-cards', 'CardsController@getCards')->name('get-cards');
});

Auth::routes();


