@extends('layouts/main_layout')

@section('content')
    <div id='edit-comment-outline'>
        <h1 id='edit-comment-header'>{{ __('text.edit_comment') }}</h1>
        <form name='edit-comment-form' id='edit-comment-form' method='post' action='{{ route('update_comment', ['comment' => $comment]) }}'>
            @csrf
            @method('PUT')

            @php
                // check from which page user arrived, so we can then redirect him back accordingly
                $prev_page = url()->previous();
            @endphp

            <input type="hidden" name='target_page' value='{{ $prev_page }}'>
            <label id='content-label' for='content'>{{ __('text.comment_text') }}</label>
            <textarea onkeydown=adjust(this) form='edit-comment-form' name='content' id='content' rows='12'>{{ $content }}</textarea>
            @error('content')
                <strong><p class='error-msg'>{{ $errors->first('content') }}</p></strong>
            @enderror

            <button id='edit-comment-button' type='submit'>{{ __('text.edit') }}</button>
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection