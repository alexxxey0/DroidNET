@extends('layouts/main_layout')
@section('content')
    @if (count($users_info) > 0)
        @foreach ($users_info as $user)
            @php
                $user_img = isset($user['image']) ? $user['image'] : 'default_image.jpg';
            @endphp

            @if ($user['unread'])
            <div class='chat_with unread'>
            @else
            <div class='chat_with'>
            @endif
                <div class='msg_author'>
                    <a class='img_link' href="{{ route('open_chat', $user['username']) }}"><img src="{{ asset('images/' . $user_img) }}" alt=""></a>
                    <a class='name_link' href="{{ route('open_chat', $user['username']) }}"><h2>{{ $user['first_name'] }} {{ $user['last_name'] }}</h2></a>
                </div>
                @if ($user['sent_last'])
                    <p>{{ $user['last_message'] }}</p>
                @else
                    <p><strong>You</strong>: {{ $user['last_message'] }}</p>
                @endif
            </div>
            <hr>
        @endforeach
    @else
        <h2>You have no chats</h2>
    @endif
@endsection