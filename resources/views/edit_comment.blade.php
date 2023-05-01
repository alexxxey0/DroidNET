@extends('layouts/main_layout')

@section('content')
    <div id='edit-comment-outline'>
        <h1 id='edit-comment-header'>Edit comment</h1>
        <form name='edit-comment-form' id='edit-comment-form' method='post' action='{{ route('update_comment', ['comment' => $comment]) }}'>
            @csrf
            @method('PUT')

            <label id='content-label' for='content'>Comment text</label>
            <textarea onkeydown=adjust(this) form='edit-comment-form' name='content' id='content' rows='12'>{{ $content }}</textarea>
            @error('content')
                <strong><p class='error-msg'>{{ $errors->first('content') }}</p></strong>
            @enderror

            <button id='edit-comment-button' type='submit'>Edit</button>
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection