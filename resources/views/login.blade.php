@extends('layouts/main_layout')

@section('content')
    <div id='login-outline'>
        <h1 id='login-header'>{{ __('text.login') }}</h1>
        <form name='login-form' id='login-form' method='POST' action='{{ route('authenticate') }}'>
            @csrf
            
            <label for='username'>{{ __('text.username') }}</label>
            <input type='text' name='username' value='{{old('username')}}'>
            @error('username')
                <strong><p class='error-msg'>{{ $errors->first('username') }}</p></strong>
            @enderror

            <label for='password'>{{ __('text.password') }}</label>
            <input type='password' name='password' id='password' value='{{old('password')}}'>
            @error('password')
                <strong><p class='error-msg'>{{ $errors->first('password') }}</p></strong>
            @enderror

            <button id='login-button' type='submit'>{{ __('text.login_confirm') }}</button>
        </form>
    </div>

@endsection