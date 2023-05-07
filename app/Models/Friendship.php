<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'request_sender', 'request_receiver', 'status', 'created_at', 'updated_at'];

    // check if 2 users are friends
    public static function are_friends($user1, $user2) {
        if (Friendship::where('friend1', '=', $user1)->where('friend2', '=', $user2)->where('status', '=', 'ACCEPTED')->count() > 0 ||
            Friendship::where('friend1', '=', $user2)->where('friend2', '=', $user1)->where('status', '=', 'ACCEPTED')->count() > 0) return true;
        else return false;
    }
}
