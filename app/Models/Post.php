<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'author', 'title', 'content', 'created_at', 'updated_at'];

    public static function posters_info() {
        $posters_info = Post::join('users', 'users.username', '=', 'posts.author')->
                                    select('users.first_name', 'users.last_name', 'users.username', 'users.image', 'users.role')->get();
        return $posters_info;
    }
}
