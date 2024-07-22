
<?php

use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'users'],function(){

    Route::get('/', [UserController::class, 'getData']);
    Route::get('/types', [UserController::class, 'getUserType']);
    Route::get('/{id}',[UserController::class, 'view']);
    Route::post('/create',[UserController::class,'createUser']);
    Route::post('/{id}',[UserController::class,'update']);
    Route::delete('/{id}',[UserController::class,'delete']);
    Route::post('/change_password/{id}',[UserController::class,'changePassword']);

});

Route::group(['prefix'=>'subject'],function(){
     
    Route::get('/',[SubjectController::class,'getData']);
    
});

Route::group(['prefix' => 'score'],function(){

    Route::get('/getscore',[ScoreController::class,'getAll']);
    Route::post('/',[ScoreController::class,'store']);
    Route::get('/{id}',[ScoreController::class,'view']);
    Route::post('/update',[ScoreController::class,'update']);
    Route::delete('/{id}',[ScoreController::class ,'delete']);
    // Route::get('/search/{name}',[ScoreController::class, 'searchScores']);

});

Route::group(['prefix'=> 'grade'],function(){
    Route::get('/',[GradeController::class,'getData']);
    Route::get('/{id}',[GradeController::class,'grade']);

});

Route::group(['prefix'=> 'profile'],function(){
    Route::get('/',[ProfileController::class,'view']);
    Route::post('/change-pass',[ProfileController::class,'changePassword']);
    Route::post('/{id}',[ProfileController::class,'update']);
});