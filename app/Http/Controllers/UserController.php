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

        return view('user', [
            'user' => User::select('*')->where('username', '=', $username)->get(),
            'users' => User::select('username', 'image')->get(),
            'posts' => $posts,
            'title' => 'User: ' . $username,
            'page' => 'user_page',
            'comments' => $comments,
            'commenters_info' => $commenters_info,
            'request_sent' => $request_sent,
            'request_received' => $request_received,
            'are_friends' => $are_friends
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

}
