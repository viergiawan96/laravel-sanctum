<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//ROUTING YANG INGIN DIPROTECT DENGAN AIRLOCK, MAKA HARUS MENGGUNAKAN MIDDLEWARE DARI AUTH:AIRLOCK
Route::group(['middleware' => 'auth:sanctum'], function() {
    //SEHINGGA SEMUA ROUTING YANG ADA DI DALAMNYA HARUS MENGIRIMKAN TOKEN
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::get('/users/tokens', 'UserController@getAllUserToken');
    Route::get('/users/delete', 'UserController@revokeToken');
});

Route::post('/login', 'UserController@login');

