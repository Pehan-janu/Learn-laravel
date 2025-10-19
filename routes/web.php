<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $posts = [];
    //$posts = Post::all();
   // $posts = Post::where('user_id', Auth::id())->get();
   if (Auth::check()){
   $posts = Auth::user()->userCoolPosts()->latest()->get();

   }
    return view('home', ['posts' => $posts]);
});

Route::post('/register', [UserController::class, 'register']);

Route::post('/logout', [UserController::class, 'logout']);

Route::post('/login', [UserController::class, 'login']);

//Blog post realed routes
Route::post('/create-post', [PostController::class, 'createPost' ]);
Route::get('/edit-post/{post}', [PostController::class, 'showEditScreen']);
Route::put('/edit-post/{post}', [PostController::class, 'actualyUpdatePost']);
Route::delete('/delete-post/{post}', [PostController::class, 'deletePost']);
