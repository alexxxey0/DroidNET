@extends('layouts/main_layout')

@section('content')
    <div id='login-outline'>
        <h1 id='login-header'>Log In</h1>
        <form name='login-form' id='login-form' method='POST' action='{{ route('authenticate') }}'>

            @csrf
            <label for='username'>Username</label>
            <input type='text' name='username' value='{{old('username')}}'>
            @error('username')
                <strong><p class='error-msg'>{{ $errors->first('username') }}</p></strong>
            @enderror

            <label for='password'>Password</label>
            <input type='password' name='password' id='password' value='{{old('password')}}'>
            @error('password')
                <strong><p class='error-msg'>{{ $errors->first('password') }}</p></strong>
            @enderror

            <button id='login-button' type='submit'>Log In!</button>
        </form>
    </div>
@endsection