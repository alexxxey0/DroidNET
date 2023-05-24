@extends('layouts/main_layout')

@section('content')
    <div id='reg-outline'>
        <h1 id='reg-header'>{{ __('text.registration') }}</h1>
        <form name='reg-form' id='reg-form' method='POST' action='{{ route('create_user') }}' enctype="multipart/form-data">
            @csrf
            
            <label for='username'>{{ __('text.username') }}</label>
            <input type='text' name='username' value='{{old('username')}}'>
            @error('username')
                <strong><p class='error-msg'>{{ $errors->first('username') }}</p></strong>
            @enderror

            <label for='first_name'>{{ __('text.first_name') }}</label>
            <input type='text' name='first_name' value='{{old('first_name')}}'>
            @error('first_name')
                <strong><p class='error-msg'>{{ $errors->first('first_name') }}</p></strong>
            @enderror

            <label for='last_name'>{{ __('text.last_name') }}</label>
            <input type='text' name='last_name' value='{{old('last_name')}}'>
            @error('last_name')
                <strong><p class='error-msg'>{{ $errors->first('last_name') }}</p></strong>
            @enderror

            <label for='password'>{{ __('text.password') }}</label>
            <input type='password' name='password' id='password' value='{{old('password')}}'>
            @error('password')
                <strong><p class='error-msg'>{{ $errors->first('password') }}</p></strong>
            @enderror

            <label for='email'>{{ __('text.email') }}</label>
            <input type='email' name='email' id='email' value='{{old('email')}}'>
            @error('email')
                <strong><p class='error-msg'>{{ $errors->first('email') }}</p></strong>
            @enderror

            <label id='image-label' for='image'>{{ __('text.image') }}</label>
            <input type='file' name='image' id='image' value='{{old('image')}}'>
            @error('image')
                <strong><p class='error-msg'>{{ $errors->first('image') }}</p></strong>
            @enderror

            <label id='about_me_label' for='about_me'>{{ __('text.about_me') }}</label>
            <textarea onkeydown=adjust(this) form='reg-form' name='about_me' id='about_me' rows='8'>{{ old('about_me') }}</textarea>
            <button id='reg-button' type='submit'>{{ __('text.register_confirm') }}</button>
        </form>
    </div>
    
    <script src="{{ asset('js/main.js') }}"></script>
@endsection