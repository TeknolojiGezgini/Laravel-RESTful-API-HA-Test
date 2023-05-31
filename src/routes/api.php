<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Crud;

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

Route::middleware('auth:api')->group(function(){
    Route::post('create', [Crud::class, 'create'])->middleware('validation');
    Route::post('update', [Crud::class, 'update'])->middleware('validation');
    Route::post('delete', [Crud::class, 'delete']);
    Route::post('list', [Crud::class, 'list']);
});