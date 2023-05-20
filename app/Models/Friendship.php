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
        if (Friendship::where('request_sender', '=', $user1)->where('request_receiver', '=', $user2)->where('status', '=', 'ACCEPTED')->count() > 0 ||
            Friendship::where('request_sender', '=', $user2)->where('request_receiver', '=', $user1)->where('status', '=', 'ACCEPTED')->count() > 0) return true;
        else return false;
    }
}
