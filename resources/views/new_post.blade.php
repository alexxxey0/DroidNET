@extends('layouts/main_layout')

@section('content')
    <div id='new-post-outline'>
        <h1 id='new-post-header'>{{ __('text.create_post') }}</h1>
        <form name='new-post-form' id='new-post-form' method='POST' action='{{ route('add_post') }}'>
            @csrf
            
            <label for='title'>{{ __('text.post_title') }}</label>
            <input type='text' name='title' value='{{old('title')}}'>
            @error('title')
                <strong><p class='error-msg'>{{ $errors->first('title') }}</p></strong>
            @enderror

            <label id='content-label' for='content'>{{ __('text.post_text') }}</label>
            <textarea onkeydown=adjust(this) form='new-post-form' name='content' id='content' rows='12'>{{ old('content') }}</textarea>
            @error('content')
                <strong><p class='error-msg'>{{ $errors->first('content') }}</p></strong>
            @enderror

            <button id='new-post-button' type='submit'>{{ __('text.post_confirm') }}</button>
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection