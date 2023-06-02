<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

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
        elseif ($locale == 'lv') $message = 'Raksts ir veiksmīgi izveidots!';
        return redirect('user/' . $username)->with(['message' => $message]);
    }

    public function delete_post(Request $request) {
        Post::where('id', '=', $request['post_id'])->delete();

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Post deleted successfully!';
        elseif ($locale == 'lv') $message = 'Raksts veiksmīgi izdzēsts!';
        return response()->json(['message' => $message]);
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
        elseif ($locale == 'lv') $message = 'Raksts ir veiksmīgi rediģēts!';
        return redirect('user/' . $username . '#post-buttons' . $post['id'])->with(['message' => $message]);
    }

    public function show_feed($sort_by) {;
        $friendships = Friendship::where(function($query) {
            $query->where('request_receiver', '=', auth()->user()->username)->orWhere('request_sender', '=', auth()->user()->username);
        })->where('status', '=', 'ACCEPTED')->get();

        $friends = array();
        foreach ($friendships as $friendship) {
            if ($friendship['request_receiver'] != auth()->user()->username) $friends[] = $friendship['request_receiver'];
            else $friends[] = $friendship['request_sender'];
        }

        if ($sort_by == 'new') $posts = Post::whereIn('author', $friends)->orderBy('created_at', 'DESC')->get();
        elseif ($sort_by == 'best') $posts = Post::whereIn('author', $friends)->orderBy('like_count', 'DESC')->orderBy('created_at', 'DESC')->get();
        //dd($sort_by);

        $comments = Comment::whereIn('post', $posts->pluck('id'))->orderBy('created_at', 'DESC')->get();
        $commenters_info = Comment::commenters_info();
        $posters_info = Post::posters_info();

        $liked_posts = Like::select('post')->where('user', '=', auth()->user()->username)->pluck('post')->toArray();


        return view('feed', [
            'title' => __('text.feed'),
            'page' => 'feed',
            'posts' => $posts,
            'comments' => $comments,
            'commenters_info' => $commenters_info,
            'posters_info' => $posters_info,
            'liked_posts' => $liked_posts,
            'sort_by' => $sort_by
        ]);
    }


    public function like_post(Request $request) {
        $post = Post::where('id', '=', $request['post'])->first();

        $liked = Like::where('post', '=', $request['post'])->where('user', '=', auth()->user()->username)->first();

        // If user hasn't liked this post yet
        if ($liked === null) {
            $like_count = $post['like_count'];
            $like_count++;
            $post->timestamps = false;
            $post->update(['like_count' => $like_count]);
            $post->timestamps = true;

            $form_fields = array();
            $form_fields['post'] = $request['post'];
            $form_fields['user'] = auth()->user()->username;
            Like::create($form_fields);

            return response()->json(['liked' => false, 'post' => $request['post']]);

        } else {
            $like_count = $post['like_count'];
            $like_count--;
            $post->timestamps = false;
            $post->update(['like_count' => $like_count]);
            $post->timestamps = true;

            $liked = Like::where('post', '=', $request['post'])->where('user', '=', auth()->user()->username)->delete();

            return response()->json(['liked' => true, 'post' => $request['post']]);
        }
    }

}
 