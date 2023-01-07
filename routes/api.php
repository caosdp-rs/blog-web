<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public routes
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// Protected Routes
Route::group(['middleware'=>['auth:sanctum']], function(){
    // User 
    Route::get('/user',[AuthController::class,'user']);
    Route::put('/user',[AuthController::class,'update']);
    Route::post('/logout',[AuthController::class,'logout']);

    // Post
    Route::get('/posts',[PostController::class,'index']); //all posts
    Route::post('/posts',[PostController::class,'store']); // create post
    Route::get('/posts/{id}',[PostController::class,'show']); //get a simple post
    Route::put('/posts/{id}',[PostController::class,'update']); // atualiza o post
    Route::delete('/posts/{id}',[PostController::class,'destroy']); // delete a post

    // Comment
    Route::get('/posts/{id}/comments',[CommentController::class,'index']); //all coments of a post
    Route::post('/posts/{id}/comments',[CommentController::class,'store']); // create a comment no post
    Route::put('/comments/{id}',[CommentController::class,'update']);
    Route::delete('/comments/{id}',[CommentController::class,'destroy']);

    // Like
    Route::post('/posts/{id}/likes',[LikeController::class,'likeOrUnlike']); // like or deslike
});

