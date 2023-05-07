<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendshipController extends Controller
{
    public function send_request(Request $request) {
        $form_fields = $request->all();
        $form_fields['status'] = 'PENDING';

        $declined_request = Friendship::where('request_sender', '=', $request['request_receiver'])->
            where('request_receiver', '=', $request['request_sender'])->where('status', '=', 'DECLINED')->first();

        // if the request was previously declined, then update the existing one instead of creating a new one
        if ($declined_request !== null) {
            $declined_request->update([
                'status' => 'PENDING',
                'request_sender' => auth()->user()->username,
                'request_receiver' => $request['request_receiver']
            ]);

            return redirect()->back();
        }


    
        try {
            Friendship::create($form_fields);
          
        } catch (\Illuminate\Database\QueryException $e) {
              return redirect()->back()->with(['message' => "You've already sent a friend request to this user!"]);
        }
        

        return redirect()->back()->with(['message' => 'A friend request has been sent to ' . $request['full_name']]);
    }

    public function accept_decline_request(Request $request) {
        //dd($request->input('accept_decline'));


        if ($request->input('accept_decline') == 'accept') {
            Friendship::where('request_receiver', '=', auth()->user()->username)
            ->where('request_sender', '=', $request['request_sender'])->update(['status' => 'ACCEPTED']);

            return redirect()->back();
        } else {
            Friendship::where('request_receiver', '=', auth()->user()->username)
            ->where('request_sender', '=', $request['request_sender'])->update(['status' => 'DECLINED']);

            return redirect()->back();
        }
    }

    public function show_friends($username) {
        return view('friends', [
            'title' => $username . "'s friends",
            'page' => 'friends_page'
        ]);
    }
}
