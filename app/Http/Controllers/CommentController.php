<?php

namespace App\Http\Controllers;

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
        ], [
            'content.required' => 'Comment text is required'
        ]);

        $form = '#comment_form' . $request['post_id'];
 
        if ($validator->fails()) return redirect(url()->previous().$form)->withErrors($validator, $request['post_id']);

        $form_fields = $request->all();
        $form_fields['post'] = $request['post_id'];
        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        Comment::create($form_fields);
        return redirect(url()->previous().$form);
        
    }
}
