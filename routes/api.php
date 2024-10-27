<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['prefix'=>'user'],function (){
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('verify',[VerificationController::class, 'verifyCode']);
});

Route::controller(TagController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('tags',[TagController::class,'index']);
    Route::post('storeTag',[TagController::class,'store']);
    Route::post('updateTag',[TagController::class,'update']);
    Route::post('deleteTag',[TagController::class,'delete']);
});

Route::controller(PostController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('posts','index');
    Route::post('storePost','store');
    Route::post('singlePost','singlePost');
    Route::post('updatePost','update');
    Route::post('deletePost','delete');
    Route::get('deletedPosts','getDeletedPosts');
    Route::post('restorePost','restorePost');
    Route::get('pinnedPosts','getPinnedPosts');
});
Route::controller(StatusController::class)->group(function(){
    Route::get('AllUsers','NumberOfAllUsers');
    Route::get('AllPosts','NumberOfAllPosts');
    Route::get('ZeroPosts','NumberOfUsersWithZeroPosts');
});





