@extends('layouts/main_layout')

@section('content')
    <div id='edit-post-outline'>
        <h1 id='edit-post-header'>{{ __('text.edit_post') }}</h1>
        <form name='edit-post-form' id='edit-post-form' method='POST' action='{{ route('update_post', ['post' => $post]) }}'>
            @csrf
            @method('PUT')
            
            <label for='title'>{{ __('text.post_title') }}</label>
            <input type='text' name='title' value='{{$post_title}}'>
            @error('title')
                <strong><p class='error-msg'>{{ $errors->first('title') }}</p></strong>
            @enderror

            <label id='content-label' for='content'>{{ __('text.post_text') }}</label>
            <textarea onkeydown=adjust(this) form='edit-post-form' name='content' id='content' rows='12'>{{ $content }}</textarea>
            @error('content')
                <strong><p class='error-msg'>{{ $errors->first('content') }}</p></strong>
            @enderror

            <button id='edit-post-button' type='submit'>{{ __('text.edit_post') }}</button>
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection