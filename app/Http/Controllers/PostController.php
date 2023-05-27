<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    // Show "Create a new post" form
    public function new_post() {
        return view('new_post', [
            'title' => __('text.create_post'),
            'page' => 'new_post'
        ]);
    }

    // Add a new post to the database
    public function add_post(Request $request) {
        $form_fields = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        Post::create($form_fields);

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Post created successfully!';
        elseif ($locale == 'lv') $message = 'Ziņa ir veiksmīgi izveidota!';
        return redirect('user/' . $username)->with(['message' => $message]);
    }

    public function delete_post(Request $request) {
        Post::where('id', '=', $request['post_id'])->delete();
        return response()->json(['message' => 'Post deleted successfully!']);
    }

    public function edit_post(Post $post) {
        return view('edit_post', [
            'post' => $post['id'],
            'post_title' => $post['title'],
            'content' => $post['content'],
            'title' => __('text.edit_post'),
            'page' => 'edit_post'
        ]);
    }

    public function update_post(Request $request, Post $post) {

        $form_fields = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $username = auth()->user()->username;
        $form_fields['author'] = $username;
        $post->update($form_fields);

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Post edited successfully!';
        elseif ($locale == 'lv') $message = 'Ziņa ir veiksmīgi rediģēta!';
        return redirect('user/' . $username . '#post-buttons' . $post['id'])->with(['message' => $message]);
    }

    public function show_feed() {
        $friendships = Friendship::where(function($query) {
            $query->where('request_receiver', '=', auth()->user()->username)->orWhere('request_sender', '=', auth()->user()->username);
        })->where('status', '=', 'ACCEPTED')->get();

        $friends = array();
        foreach ($friendships as $friendship) {
            if ($friendship['request_receiver'] != auth()->user()->username) $friends[] = $friendship['request_receiver'];
            else $friends[] = $friendship['request_sender'];
        }

        $posts = Post::whereIn('author', $friends)->orderBy('created_at', 'DESC')->get();
        $comments = Comment::whereIn('post', $posts->pluck('id'))->orderBy('created_at', 'DESC')->get();
        $commenters_info = Comment::commenters_info();
        $posters_info = Post::posters_info();


        return view('feed', [
            'title' => __('text.feed'),
            'page' => 'feed',
            'posts' => $posts,
            'comments' => $comments,
            'commenters_info' => $commenters_info,
            'posters_info' => $posters_info
        ]);
    }

}
 