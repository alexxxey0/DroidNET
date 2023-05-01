<?php
namespace App\Models;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Welcome page
Route::get('/', function() {
    return view('welcome', [
        'title' => 'Welcome to DroidNET!',
        'page' => 'welcome'
    ]);
})->name('/');

// User profile page
Route::get('/user/{username}', [UserController::class, 'user_profile'])->name('user');

// Registration page
Route::get('/register', [UserController::class, 'register'])->name('register');

// Create a new user
Route::post('/create_user', [UserController::class, 'create_user'])->name('create_user');

// Show login page
Route::get('/login', [UserController::class, 'login'])->name('login');

// Login user
Route::post('/authenticate', [UserController::class, 'authenticate'])->name('authenticate');

// Log out
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Show "Create a new post" form
Route::get('/new_post', [PostController::class, 'new_post'])->name('new_post');

// Add new post to the database
Route::post('/new_post', [PostController::class, 'add_post'])->name('add_post');

// Add a new comment
Route::post('/add_comment', [CommentController::class, 'add_comment'])->name('add_comment');

//Delete a post
Route::delete('/delete_post', [PostController::class, 'delete_post'])->name('delete_post');

// Show edit post form
Route::get('/edit_post/{post}', [PostController::class, 'edit_post'])->name('edit_post');

// Update a post
Route::put('/update_post/{post}', [PostController::class, 'update_post'])->name('update_post');

// Delete a comment
Route::delete('/delete_comment', [CommentController::class, 'delete_comment'])->name('delete_comment');

// Show edit comment form
Route::get('/edit_comment/{comment}', [CommentController::class, 'edit_comment'])->name('edit_comment');

// Update a comment
Route::put('/update_comment/{comment}', [CommentController::class, 'update_comment'])->name('update_comment');
