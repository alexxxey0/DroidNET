<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function user_profile($username) {
        $posts = Post::select('*')->where('author', '=', $username)->orderBy('created_at', 'DESC')->get(); // select all posts made by the user

        $post_ids = Post::select('id')->where('author', '=', $username)->get();
        $comments = Comment::select('*')->whereIn('post', $post_ids)->orderBy('created_at', 'DESC')->get(); // select all comments belonging to user's posts

        $commenters_info = Comment::commenters_info();

        $full_name = User::select('first_name', 'last_name')->where('username', '=', $username)->get();
        $user_role = User::select('role')->where('username', '=', $username)->get();

        $friend_count = Friendship::where(function($query) use ($username) {
            $query->where('request_receiver', '=', $username)->orWhere('request_sender', '=', $username);
        })->where('status', '=', 'ACCEPTED')->count();

        if (Auth::check()) {
            $requests_sent = Friendship::select('request_receiver')->where('request_sender', '=', auth()->user()->username)
            ->where(function($query) {
                $query->where('status', '=', 'PENDING')->orWhere('status', '=', 'DECLINED');
            })->pluck('request_receiver')->toArray();
            $request_sent = in_array($username, $requests_sent); // check if current user has sent a request to this user and it's still pending

            $requests_received = Friendship::select('request_sender')->where('request_receiver', '=', auth()->user()->username)
            ->where('status', '=', 'PENDING')->pluck('request_sender')->toArray();
            $request_received = in_array($username, $requests_received); // check if current user has received a request from this user and it's still pending

            $are_friends = Friendship::are_friends(auth()->user()->username, $username);
        } else {
            $request_sent = false;
            $request_received = false;
            $are_friends = false;
        }

        if ($full_name->isEmpty()) $title = null;
        else $title = $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'];

        if ($user_role->isEmpty()) $role = null;
        else $role = $user_role[0]['role'];

        return view('user', [
            'user' => User::select('*')->where('username', '=', $username)->get(),
            //'user_obj' => User::select('*')->where('username', '=', $username)->first(),
            'users' => User::select('username', 'image')->get(),
            'posts' => $posts,
            'title' => $title,
            'page' => 'user_page',
            'comments' => $comments,
            'commenters_info' => $commenters_info,
            'request_sent' => $request_sent,
            'request_received' => $request_received,
            'are_friends' => $are_friends,
            'friend_count' => $friend_count,
            'user_role' => $role
        ]);
    }

    // Show registration form

    public function register() {
        return view('register', [
            'title' => 'Register',
            'page' => 'registration_page'
        ]);
    }

    // Create a new user

    public function create_user(Request $request) {
        $form_fields = $request->validate([
            'username' => ['required', Rule::unique('users', 'username'), 'max:50'],
            'first_name' => ['required', 'max:20'],
            'last_name' => ['required', 'max:20'],
            'password' => ['required', 'min:6'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'image' => 'image|mimes:jpg,png,jpeg,svg'
        ]);
        $form_fields['about_me'] = $request['about_me'];

        if (isset($form_fields['image'])) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $form_fields['image'] = $imageName;
        }

        // Hash password
        $form_fields['password'] = bcrypt($form_fields['password']);

        $user = User::create($form_fields);
        auth()->login($user);

        return redirect('user/' . $form_fields['username'])->with(['message' => 'Registration successful!']); // redirect user to his profile page
    }

    // Log out

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }

    // Show Log In form

    public function login () {
        return view('login', [
            'title' => 'Log In',
            'page' => 'login_page'
        ]);
    }

    // Log In user

    public function authenticate(Request $request) {
        $form_fields = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (auth()->attempt($form_fields)) {
            $request->session()->regenerate();
            return redirect('user/' . $form_fields['username']);
        }

        return back()->withErrors(['password' => 'Invalid credentials'])->withInput();
    }

    public function show_settings() {
        return view('settings', [
            'title' => 'Settings',
            'page' => 'settings'
        ]);
    }

    public function edit_profile() {
        return view('edit_profile', [
            'title' => 'Edit profile details',
            'page' => 'edit_profile'
        ]);
    }

    public function update_profile(Request $request) {

        $user = User::where('username', '=', $request['user'])->get()->first();

        $form_fields = $request->validate([
            'first_name' => ['required', 'max:20'],
            'last_name' => ['required', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user['email'], 'email')],
            'image' => 'image|mimes:jpg,png,jpeg,svg',
            'password' => ['nullable', 'min:6', 'confirmed'],
            'password_confirmation' => ['nullable', 'min:6']
        ], [
            'password.confirmed' => 'Passwords do not match'
        ]);

        if (!isset($form_fields['password'])) $form_fields['password'] = $user['password']; 
        else $form_fields['password'] = bcrypt($form_fields['password']);

        $form_fields['about_me'] = $request['about_me'];

        if (isset($form_fields['image'])) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $form_fields['image'] = $imageName;
        } else $form_fields['image'] = $user['image'];

        $user->update($form_fields);
        return redirect('user/' . $user['username'])->with(['message' => 'Profile edited successfully!']);

    }

    public function search_page() {
        return view('search_page', [
            'title' => 'Search for people',
            'page' => 'search_page'
        ]);
    }

    public function show_search_results(Request $request) {
        $search_results = User::search($request['search_input']);

        return view('search_page', [
            'title' => 'Search for people',
            'page' => 'search_page',
            'search_results' => $search_results
        ]);
    }

}
