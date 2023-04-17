<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class UserController extends Controller
{
    public function show_posts($username) {
        $posts = Post::select('*')->where('author', '=', $username)->get(); // select all posts made by the user
        $post_ids = Post::select('id')->where('author', '=', $username)->get();
        return view('user', [
            'user' => User::select('*')->where('username', '=', $username)->get(),
            'posts' => $posts,
            'comments' => Comment::select('*')->whereIn('post', $post_ids)->get(), // select all comments, belonging to user's posts
            'title' => 'User: ' . $username,
            'page' => 'user_page'
        ]);
    }

    // Show registration form

    public function register() {
        return view('register', [
            'title' => 'Register',
            'page' => 'registration_page'
        ]);
    }
}
