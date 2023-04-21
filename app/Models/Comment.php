<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Get each commenters' username, profile picture, first name and last name
    public static function commenters_info() {
        $commenters_info = Comment::join('users', 'users.username', '=', 'comments.author')->
                                    select('comments.author', 'users.image', 'users.first_name', 'users.last_name')->get();
        return $commenters_info;
    }
}
