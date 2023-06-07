<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use IntlDateFormatter;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
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

        $locale = Config::get('app.locale');
        if (empty($locale)) $locale = 'en';
        if ($locale == 'lv') {
            $fmt = new IntlDateFormatter( "lv_LV" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
        } elseif ($locale == 'en') {
            $fmt = new IntlDateFormatter( "en_GB" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
        }
        $time = ucfirst(datefmt_format($fmt, time()));


        $comment = Comment::create($form_fields);
        $id = $comment['id'];
        return response()->json(['name' => $name, 'image' => $image, 'content' => $form_fields['content'], 'post_id' => $form_fields['post'], 'profile_link' => route('user', $username), 'time' => $time, 'id' => $id, 'edit_link' => route('edit_comment', $id)]);
        
    }

    public function delete_comment(Request $request) {
        Comment::where('id', '=', $request['comment_id'])->delete();

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Comment deleted successfully!';
        elseif ($locale == 'lv') $message = 'Komentārs veiksmīgi izdzēsts!';
        return response()->json(['message' => $message]);
    }

    public function edit_comment(Comment $comment) {
        return view('edit_comment', [
            'comment' => $comment['id'],
            'content' => $comment['content'],
            'title' => __('text.edit_comment'),
            'page' => 'edit_comment'
        ]);
    }

    public function update_comment(Request $request, Comment $comment) {
        $form_fields = $request->validate([
            'content' => 'required'
        ]);

        $username = auth()->user()->username;
        $form_fields['author'] = $username;

        $post = Post::select('author')->where('id', '=', $comment['post'])->get();

        $comment->update($form_fields);

        
        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Comment edited successfully!';
        elseif ($locale == 'lv') $message = 'Komentārs ir veiksmīgi rediģēts!';

        
        $target_page = $request['target_page'];
        if (str_contains($target_page, 'user')) return redirect('user/' . $post[0]['author'] . '#comment-buttons' . $comment['id'])->with(['message' => $message]);
        else {
            if (str_contains($target_page, 'new')) return redirect('feed/new/' . '#comment-buttons' . $comment['id'])->with(['message' => $message]);
            elseif (str_contains($target_page, 'best')) return redirect('feed/best/' . '#comment-buttons' . $comment['id'])->with(['message' => $message]);
        }
    }
}
