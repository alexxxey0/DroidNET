<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class UserController extends Controller
{
    public function user_profile($username) {
        $posts = Post::select('*')->where('author', '=', $username)->orderBy('created_at', 'DESC')->get(); // select all posts made by the user

        $post_ids = Post::select('id')->where('author', '=', $username)->get();
        $comments = Comment::select('*')->whereIn('post', $post_ids)->orderBy('created_at', 'DESC')->get(); // select all comments belonging to user's posts

        $commenters_info = Comment::commenters_info();

        return view('user', [
            'user' => User::select('*')->where('username', '=', $username)->get(),
            'users' => User::select('username', 'image')->get(),
            'posts' => $posts,
            'title' => 'User: ' . $username,
            'page' => 'user_page',
            'comments' => $comments,
            'commenters_info' => $commenters_info
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
