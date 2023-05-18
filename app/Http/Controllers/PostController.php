<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    // Show "Create a new post" form
    public function new_post() {
        return view('new_post', [
            'title' => 'Create a new post',
            'page' => 'new_post'
        ]);
    }

    // Add a new post to the database
    public function add_post(Request $request) {
        $form_fields = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ], [
            'title.required' => 'Post title is required',
            'content.required' => 'Post text is required'
        ]);

        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        Post::create($form_fields);
        return redirect('user/' . $username)->with(['message' => 'Post created successfully!']);
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
            'title' => 'Edit post',
            'page' => 'edit_post'
        ]);
    }

    public function update_post(Request $request, Post $post) {

        $form_fields = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ], [
            'title.required' => 'Post title is required',
            'content.required' => 'Post text is required'
        ]);

        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        $post->update($form_fields);
        return redirect('user/' . $username . '#post-buttons' . $post['id'])->with(['message' => 'Post edited successfully!']);
    }

    public function show_feed() {
        $friendships = Friendship::where(function($query) {
            $query->where('friend1', '=', auth()->user()->username)->orWhere('friend2', '=', auth()->user()->username);
        })->where('status', '=', 'ACCEPTED')->get();

        $friends = array();
        foreach ($friendships as $friendship) {
            if ($friendship['friend1'] != auth()->user()->username) $friends[] = $friendship['friend1'];
            else $friends[] = $friendship['friend2'];
        }
        $friends_info = User::whereIn('username', $friends)->get();
        //dd($friends_info);

        $posts = Post::whereIn('author', $friends)->orderBy('created_at', 'DESC')->get();
        $comments = Comment::whereIn('post', $posts->pluck('id'))->orderBy('created_at', 'DESC')->get();
        $commenters_info = Comment::commenters_info();
        $posters_info = Post::posters_info();


        return view('feed', [
            'title' => 'Feed',
            'page' => 'feed',
            'friends_info' => $friends_info,
            'posts' => $posts,
            'comments' => $comments,
            'commenters_info' => $commenters_info,
            'posters_info' => $posters_info
        ]);
    }

}
 