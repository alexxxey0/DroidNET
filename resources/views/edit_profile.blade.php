@extends('layouts/main_layout')

@section('content')
    <div id='edit_profile-outline'>
        <h1 id='edit_profile-header'>Edit profile details</h1>
        <form name='edit_profile-form' id='edit_profile-form' method='POST' action='{{ route('update_profile') }}' enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name='user' value='{{ auth()->user()->username }}'>

            <label for='first_name'>First name</label>
            <input type='text' name='first_name' value='{{ auth()->user()->first_name }}'>
            @error('first_name')
                <strong><p class='error-msg'>{{ $errors->first('first_name') }}</p></strong>
            @enderror

            <label for='last_name'>Last name</label>
            <input type='text' name='last_name' value='{{ auth()->user()->last_name }}'>
            @error('last_name')
                <strong><p class='error-msg'>{{ $errors->first('last_name') }}</p></strong>
            @enderror

            <label for='email'>E-mail</label>
            <input type='email' name='email' id='email' value='{{ auth()->user()->email }}'>
            @error('email')
                <strong><p class='error-msg'>{{ $errors->first('email') }}</p></strong>
            @enderror

            <label id='image-label' for='image'>Profile picture</label>
            <input type='file' name='image' id='image'>
            @error('image')
                <strong><p class='error-msg'>{{ $errors->first('image') }}</p></strong>
            @enderror

            <label id='about_me_label' for='about_me'>Description</label>
            <textarea onkeydown=adjust(this) form='edit_profile-form' name='about_me' id='about_me' rows='8'>{{ auth()->user()->about_me }}</textarea>

            <label for='password'>Set a new password</label>
            <input type='password' name='password' id='password'>
            @error('password')
                <strong><p class='error-msg'>{{ $errors->first('password') }}</p></strong>
            @enderror

            <label for='password'>Confirm new password</label>
            <input type='password' name='password_confirmation' id='password_confirmation'>
            @error('password_confirmation')
                <strong><p class='error-msg'>{{ $errors->first('password_confirmation') }}</p></strong>
            @enderror

            <button id='edit_profile-button' type='submit'>Save changes</button>
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection