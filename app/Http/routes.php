<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function (\App\Services\AuthService $service) {

$service->createGroup([
    'group_name'    =>  '超级管理s员',
    'title' =>  '无所不能等',
]);

    return view('welcome');
});

Route::group(['middleware' => 'admin'] , function() {

    Route::match(['GET' , 'POST'] , 'auth/signIn' , 'Auth\AuthController@signIn');
});



//Route::match(['GET' , 'POST'] , 'auth/signIn' , 'Auth\AuthController@signIn');
