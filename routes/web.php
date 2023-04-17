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
Route::get('/user/{username}', [UserController::class, 'show_posts']);

// Registration page
Route::get('/register', [UserController::class, 'register'])->name('/register');
?>