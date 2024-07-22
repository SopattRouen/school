<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'auth'], function () {
    require(__DIR__ . '/api/auth.php');
});

//==========================Protect the Route from Unauthorized=========
Route::middleware(['auth'])->group(function () {


    Route::group([], function () {
        require(__DIR__ . '/api/admin.php');
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    

 });
 
