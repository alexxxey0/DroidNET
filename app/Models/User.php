<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['username', 'first_name', 'last_name', 'password', 'email', 'about_me', 'role', 'image', 'created_at', 'updated_at'];

    use HasApiTokens, HasFactory, Notifiable;

    public static function search($search_input) {
        // Return all users matching the search input except the current user
        if (Auth::check()) return User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search_input . '%')->where('username', '!=', auth()->user()->username)->get();
        else return User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search_input . '%')->get();
    }
}

