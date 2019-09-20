<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'api\UserController@login');
Route::post('register', 'api\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'api\UserController@details');
    Route::get('notificacoes', 'api\NotificacaoController@index');
});