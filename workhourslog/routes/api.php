<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'LoginController@login');
Route::post('register', 'LoginController@register');
Route::post('forgot-password','LoginController@forgotPasswordFunction');
Route::post('reset-password', 'LoginController@resetPassword');
Route::post('add-new-task', 'TaskController@add');
Route::post('edit-task/{date_type}', 'TaskController@edit');
Route::get('edit-task/{date_type}', 'TaskController@edit');
Route::get('get-task/{date_type}', 'TaskController@getTask');
Route::post('get-task/{date_type}', 'TaskController@getTask');



//Route::get('loginurl', 'LoginController@login2');
// Route::resource('SignIn', 'Api\LoginController@login');
