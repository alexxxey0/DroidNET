<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

        Friendship::create($form_fields);

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'A friend request has been sent to ' . $request['full_name'];
        elseif ($locale == 'lv') $message = 'Draudzības pieprasījums ir nosūtīts ' . $request['full_name'];
        return redirect()->back()->with(['message' => $message]);
    }

    public function accept_decline_request(Request $request) {
        //dd($request->input('accept_decline'));


        if ($request->input('accept_decline') == 'accept') {
            Friendship::where('request_receiver', '=', auth()->user()->username)
            ->where('request_sender', '=', $request['request_sender'])->update(['status' => 'ACCEPTED']);

            if ($request['tab'] == 'friends_tab') return redirect()->back()->with(['switch_tab' => 'incoming']);
            else return redirect()->back();

        } else {
            Friendship::where('request_receiver', '=', auth()->user()->username)
            ->where('request_sender', '=', $request['request_sender'])->update(['status' => 'DECLINED']);

            if ($request['tab'] == 'friends_tab') return redirect()->back()->with(['switch_tab' => 'incoming']);
            else return redirect()->back();
        }
    }

    public function show_friends($username) {

        $friendships = Friendship::where(function($query) use ($username) {
            $query->where('request_sender', '=', $username)->orWhere('request_receiver', '=', $username);
        })->where('status', '=', 'ACCEPTED')->get();
        $friends = array();
        foreach ($friendships as $friendship) {
            if ($friendship['request_sender'] != $username) $friends[] = $friendship['request_sender'];
            else $friends[] = $friendship['request_receiver'];
        }
        $friends_info = User::whereIn('username', $friends)->get();


        $incoming_requests = Friendship::select('request_sender')->where('request_receiver', '=', $username)->where('status', '=', 'PENDING')->get();
        $incoming_requests_users_info = User::whereIn('username', $incoming_requests)->get();


        $sent_requests = Friendship::select('request_receiver')->where('request_sender', '=', $username)->where('status', '=', 'PENDING')->get();
        $sent_requests_users_info = User::whereIn('username', $sent_requests)->get();


        $full_name = User::select('first_name', 'last_name')->where('username', '=', $username)->get();

        $locale = App::getLocale();
        if ($locale == 'en') $title = $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'] . "'s friends";
        elseif ($locale == 'lv') $title = $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'] . " draugi";

        return view('friends', [
            'title' => $title,
            'username' => $username,
            'full_name' => $full_name,
            'page' => 'friends_page',
            'friends_info' => $friends_info,
            'incoming_requests_users_info' => $incoming_requests_users_info,
            'sent_requests_users_info' => $sent_requests_users_info
        ]);
    }

    public function remove_friend(Request $request) {
        Friendship::where(function($query) use ($request) {
            $query->where('request_sender', '=', $request['current_user'])->where('request_receiver', '=', $request['friend']);
        })->orWhere(function($query) use ($request) {
            $query->where('request_receiver', '=', $request['current_user'])->where('request_sender', '=', $request['friend']);
        })->delete();
        
        if ($request['tab'] == 'friends_tab') return redirect()->back()->with(['switch_tab' => 'friends']);
        else return redirect()->back();
    }
}
