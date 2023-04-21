<?php
namespace App\Models;
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
