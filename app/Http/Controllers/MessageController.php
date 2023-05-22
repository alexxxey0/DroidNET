<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function open_chat($user) {
        $user_info = User::where('username', '=', $user)->get()->first();
        $messages = Message::where(function ($query) use ($user_info) {
            $query->where('message_sender', '=', auth()->user()->username)->where('message_receiver', '=', $user_info['username']);
        })->orWhere(function ($query) use ($user_info) {
            $query->where('message_receiver', '=', auth()->user()->username)->where('message_sender', '=', $user_info['username']);
        })->get();

        return view('chat', [
            'title' => 'Chat with ' . $user_info['first_name'] . ' ' . $user_info['last_name'],
            'page' => 'chat',
            'user_info' => $user_info,
            'messages' => $messages
        ]);
    }

    public function show_chats() {
        $messages = Message::select('message_sender', 'message_receiver')->where('message_sender', '=', auth()->user()->username)->orWhere('message_receiver', '=', auth()->user()->username)->get();
        
        $users = array();

        foreach ($messages as $message) {
            if ($message['message_sender'] != auth()->user()->username && !in_array($message['message_sender'], $users)) $users[] = $message['message_sender'];
            if ($message['message_receiver'] != auth()->user()->username && !in_array($message['message_receiver'], $users)) $users[] = $message['message_receiver'];
        }

        $users_info = User::whereIn('username', $users)->get();

        foreach ($users_info as $user) {
            $last_message = Message::select('content', 'message_sender')->where('message_sender', '=', $user['username'])->orWhere('message_receiver', '=', $user['username'])->orderBy('created_at', 'DESC')->first();
            $user['last_message'] = $last_message['content'];
            $user['sent_last'] = $last_message['message_sender'] == $user['username'];

            // Shorten the last message if it is too long
            if (strlen($user['last_message']) > 50) $user['last_message'] = substr($user['last_message'], 0, 50) . '...';
        }
        
        return view('chats', [
            'title' => 'Chats',
            'page' => 'chats',
            'users_info' => $users_info
        ]);
    }


    public function send_message(Request $request) {
        $form_fields = $request->validate([
            'content' => 'required'
        ], [
            'content.required' => 'Message text is required'
        ]);
        $form_fields['message_sender'] = $request['message_sender'];
        $form_fields['message_receiver'] = $request['message_receiver'];

        Message::create($form_fields);

        $time = date("h:i A, d/m/Y", time());
        $image = isset(auth()->user()->image) ? asset('images/' . auth()->user()->image) : asset('images/default_image.jpg');


        return response()->json([
            'name' => auth()->user()['first_name'] . ' ' . auth()->user()['last_name'],
            'image' => $image,
            'content' => $request['content'],
            'time' => $time
        ]);
    }
}
