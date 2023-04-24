<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        return redirect('user/' . $username)->with(['post_success' => true]);
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
        //dd($post);
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
        return redirect('user/' . $username)->with(['post_edit_success' => true]);
    }

}
 