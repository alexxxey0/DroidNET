<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function add_comment(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        $form_fields = $request->all();
        $form_fields['post'] = $request['post_id'];
        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        $name = $request['name'];
        $image = isset(auth()->user()->image) ? asset('images/' . auth()->user()->image) : asset('images/default_image.jpg');
        $time = date("M jS, Y G:i", time());

        $comment = Comment::create($form_fields);
        $id = $comment['id'];
        return response()->json(['name' => $name, 'image' => $image, 'content' => $form_fields['content'], 'post_id' => $form_fields['post'], 'profile_link' => route('user', $username), 'time' => $time, 'id' => $id, 'edit_link' => route('edit_comment', $id)]);
        
    }

    public function delete_comment(Request $request) {
        Comment::where('id', '=', $request['comment_id'])->delete();
        return response()->json(['message' => 'Comment deleted successfully!']);
    }
}
