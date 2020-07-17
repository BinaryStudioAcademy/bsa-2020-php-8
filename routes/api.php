<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
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

Route::middleware('auth')->post('/images/{imageId}', "ImageController@updateImage");

Route::middleware('auth')->get('/filters/', "ImageController@filters");

Route::post('/broadcasting/auth', function (Request $request) {
    return response()->json([], 200);
})->middleware('auth');

Route::post('/users', 'Auth\AuthController@register');
Route::post('/auth', 'Auth\AuthController@auth');
