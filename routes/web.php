<?php
namespace App\Models;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\MessageController;
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

// Send a friend request
Route::post('/send_request', [FriendshipController::class, 'send_request'])->name('send_request');

// Accept or decline a friend request
Route::put('/accept_decline', [FriendshipController::class, 'accept_decline_request'])->name('accept_decline_request');

// Show user's friends
Route::get('/user/{username}/friends', [FriendshipController::class, 'show_friends'])->name('friends');

// Remove a friend
Route::delete('/remove_friend', [FriendshipController::class, 'remove_friend'])->name('remove_friend');

// Show settings menu
Route::get('/settings', [UserController::class, 'show_settings'])->name('settings');

// Show edit profile form
Route::get('/settings/edit_profile', [UserController::class, 'edit_profile'])->name('edit_profile');

// Update a post
Route::put('/update_profile', [UserController::class, 'update_profile'])->name('update_profile');

// Show the user search page
Route::get('/search', [UserController::class, 'search_page'])->name('search_page');

// Show search results
Route::get('/search_results', [UserController::class, 'show_search_results'])->name('show_search_results');

// Show feed
Route::get('/feed', [PostController::class, 'show_feed'])->name('show_feed');

// Open a chat with another user
Route::get('/chat/{user}', [MessageController::class, 'open_chat'])->name('open_chat');

// Open user's chats
Route::get('/chats', [MessageController::class, 'show_chats'])->name('show_chats');

// Send a message
Route::post('/send_message', [MessageController::class, 'send_message'])->name('send_message');