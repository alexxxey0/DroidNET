<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
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
            'title' => __('text.register'),
            'page' => 'registration_page'
        ]);
    }

    // Create a new user

    public function create_user(Request $request) {
        $form_fields = $request->validate([
            'username' => ['required', Rule::unique('users', 'username'), 'max:50'],
            'first_name' => ['required', 'max:20'],
            'last_name' => ['required', 'max:20'],
            'password' => ['required', 'min:6', 'confirmed'],
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

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Registration successful!';
        elseif ($locale == 'lv') $message = 'Reģistrācija veiksmīga!';
        return redirect('user/' . $form_fields['username'])->with(['message' => $message]); // redirect user to his profile page
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
            'title' => __('text.log_in'),
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
            'title' => __('text.settings'),
            'page' => 'settings'
        ]);
    }

    public function edit_profile() {
        return view('edit_profile', [
            'title' => __('text.edit_profile'),
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

        $locale = App::getLocale();
        if ($locale == 'en') $message = 'Profile edited successfully!';
        elseif ($locale == 'lv') $message = 'Profils ir veiksmīgi rediģēts!';
        return redirect('user/' . $user['username'])->with(['message' => $message]);

    }

    public function search_page() {
        return view('search_page', [
            'title' => __('text.search_for_people'),
            'page' => 'search_page'
        ]);
    }

    public function show_search_results(Request $request) {
        $search_results = User::search($request['search_input']);

        return view('search_page', [
            'title' => __('text.search_for_people'),
            'page' => 'search_page',
            'search_results' => $search_results
        ]);
    }


    public function edit_mods() {
        $mods = User::where('role', '=', 'moderator')->get();

        return view('edit_mods', [
            'title' => __('text.edit_mods'),
            'page' => 'edit_mods',
            'mods' => $mods
        ]);
    }

    public function remove_mod(Request $request) {
        User::where('username', '=', $request['username'])->update(['role' => 'user']);

        return redirect()->back();
    }

    public function add_mod(Request $request) {
        $mods = User::select('username')->where('role', '=', 'moderator')->pluck('username')->toArray();
        $users = User::select('username')->pluck('username')->toArray();
        $locale = App::getLocale();
        
        if (in_array($request['username'], $mods)) {
            if ($locale == 'en') $message = 'This user is already a moderator!';
            elseif ($locale == 'lv') $message = 'Šis lietotājs jau ir moderators!';
            return redirect()->back()->with(['message' => $message]);

        } elseif (!in_array($request['username'], $users)) {
            if ($locale == 'en') $message = "This user doesn't exist!";
            elseif ($locale == 'lv') $message = 'Šis lietotājs neeksistē!';
            return redirect()->back()->with(['message' => $message]);
        }

        User::where('username', '=', $request['username'])->update(['role' => 'moderator']);
        return redirect()->back();
    }

    public function banned_users() {
        $bans = User::where('role', '=', 'banned')->get();

        return view('banned_users', [
            'title' => __('text.banned_users'),
            'page' => 'banned_users',
            'bans' => $bans

        ]);
    }

    public function ban_user(Request $request) {
        $username = $request['username'];
        $ban_reason = $request['ban_reason'];

        $banned = User::select('username')->where('role', '=', 'banned')->pluck('username')->toArray();
        $users = User::select('username')->pluck('username')->toArray();
        $role = User::select('role')->where('username', '=', $username)->pluck('role')->first();
        $current_role = auth()->user()->role;
        $locale = App::getLocale();
        
        if (in_array($username, $banned)) {
            if ($locale == 'en') $message = 'This user is already banned!';
            elseif ($locale == 'lv') $message = 'Šis lietotājs jau ir bloķēts!';
            return redirect()->back()->with(['message' => $message]);

        } elseif (!in_array($username, $users)) {
            if ($locale == 'en') $message = "This user doesn't exist!";
            elseif ($locale == 'lv') $message = 'Šis lietotājs neeksistē!';
            return redirect()->back()->with(['message' => $message]);

        } elseif ($username == auth()->user()->username) {
            if ($locale == 'en') $message = "You can't ban yourself!";
            elseif ($locale == 'lv') $message = 'Jūs nevarat bloķēt sevi!';
            return redirect()->back()->with(['message' => $message]);

        } elseif ($current_role == 'moderator' && in_array($role, ['admin', 'moderator'])) {
            if ($locale == 'en') $message = "You do not have permission to ban this user!";
            elseif ($locale == 'lv') $message = 'Jums nav atļaujas bloķēt šo lietotāju!';
            return redirect()->back()->with(['message' => $message]);
        }

        User::where('username', '=', $username)->update(['role' => 'banned', 'ban_reason' => $ban_reason]);
        return redirect()->back();
    }

    public function unban_user(Request $request) {
        $username = $request['username'];

        User::where('username', '=', $username)->update(['role' => 'user', 'ban_reason' => null]);
        return redirect()->back();
    }

}
