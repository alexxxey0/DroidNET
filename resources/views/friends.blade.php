@extends('layouts/main_layout')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @php
        $my_page = isset(auth()->user()->username) ? auth()->user()->username == $username : false;
        $locale = Config::get('app.locale');
    @endphp

    <div id="tabs">
        @if ($my_page)
            <button id='defaultOpen' class="tablinks" onclick="openTab(event, 'friends_list')">{{ __('text.friends') }}</button>
            <button id='incoming_tab' class="tablinks" onclick="openTab(event, 'incoming')">{{ __('text.incoming_requests') }}</button>
            <button class="tablinks" onclick="openTab(event, 'sent')">{{ __('text.sent_requests') }}</button>
        @else
            @if ($locale == 'en')
                <h1>{{ $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'] }}'s {{ __('text.friends_lower') }}</h1>
            @elseif ($locale == 'lv')
                <h1>{{ $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'] }} {{ __('text.friends_lower') }}</h1>
            @endif
        @endif
    </div>

    <div id='friends_list' class='tabcontent'>
        @if (count($friends_info) > 0)
            @foreach ($friends_info as $friend)
                @php
                    $friend_image = isset($friend['image']) ? $friend['image'] : 'default_image.jpg';
                @endphp

                <div class="friend_info">
                    <a href="{{ route('user', $friend['username']) }}">
                        <img src="{{ asset('images/' . $friend_image) }}" alt="Profile Pic">
                    </a>
                    <h1>{{ $friend['first_name'] . ' ' . $friend['last_name'] }}</h1>

                    @if ($my_page)
                        <form name='remove-friend-form' class='remove-friend-form' method='POST' action='{{ route('remove_friend') }}'>
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name='friend' value='{{ $friend['username'] }}'>
                            <input type="hidden" name='current_user' value='{{ auth()->user()->username }}'>
                            <input type="hidden" name='tab' value='friends_tab'>
                            <button name='remove_friend' value='remove' class='remove_friend' type='submit'>{{ __('text.remove_friend') }}</button>
                        </form>
                    @endif
                </div>
            @endforeach
        @else
            @if ($my_page)
                <h1 class='no_friends'>{{ __('text.no_friends_you') }}</h1>
            @else
                <h1 class="no_friends">{{ $full_name[0]['first_name'] . ' ' . $full_name[0]['last_name'] }} {{ __('text.no_friends_user') }}</h1>
            @endif
        @endif
    </div>

    @if ($my_page)
        <div id='incoming' class='tabcontent'>
            @if (count($incoming_requests_users_info) > 0)
                @foreach ($incoming_requests_users_info as $user)
                    @php
                        $friend_image = isset($user['image']) ? $user['image'] : 'default_image.jpg';
                    @endphp

                    <div class="friend_info">
                        <a href="{{ route('user', $user['username']) }}">
                            <img src="{{ asset('images/' . $friend_image) }}" alt="Profile Pic">
                        </a>
                        <h1>{{ $user['first_name'] . ' ' . $user['last_name'] }}</h1>

                        <form name='accept-decline-request-form' class='accept-decline-request-form' method='POST' action='{{ route('accept_decline_request') }}'>
                            @csrf
                            @method('PUT')
                            <input type='hidden' name='request_sender' value='{{ $user['username'] }}'>
                            <input type="hidden" name='tab' value='friends_tab'>
                            <button name='accept_decline' value='accept' class='accept-decline accept-request' type='submit'>{{ __('text.accept_request') }}</button>
                            <button name='accept_decline' value='decline' class='accept-decline decline-request' type='submit'>{{ __('text.decline_request') }}</button>
                        </form>
                    </div>

                @endforeach
            @else
                <h1 class='no_friends'>{{ __('text.no_incoming_requests') }}</h1>
            @endif
        </div>

        <div id='sent' class='tabcontent'>
            @if (count($sent_requests_users_info) > 0)
                @foreach ($sent_requests_users_info as $user)
                    @php
                        $friend_image = isset($user['image']) ? $user['image'] : 'default_image.jpg';
                    @endphp

                    <div class="friend_info">
                        <a href="{{ route('user', $user['username']) }}">
                            <img src="{{ asset('images/' . $friend_image) }}" alt="Profile Pic">
                        </a>
                        <h1>{{ $user['first_name'] . ' ' . $user['last_name'] }}</h1>
                    </div>

                @endforeach
            @else
                <h1 class='no_friends'>{{ __('text.no_sent_requests') }}</h1>
            @endif
        </div>
    @endif

    <script>
        function add_delete_confirmation(button) {
            $(button).on('click', function (event) {
                event.preventDefault();

                var form = $(button).closest('form'); // Get the related form
                if ('{{ $locale }}' == 'en') {
                    if ($(this).hasClass('remove_friend')) var msg = 'Are you sure you want to remove this friend?';
                } else if ('{{$locale}}' == 'lv') {
                    if ($(this).hasClass('remove_friend')) var msg = 'Vai tiešām vēlaties noņemt šo draugu?';
                }

                if (confirm(msg)) form.submit();

            })
        }

        document.getElementById("defaultOpen").click();
        $('.remove_friend').each(function(index, object) {
            add_delete_confirmation(object);
        })
    </script>

    @if (session()->get('switch_tab') == 'incoming')
        <script>
            document.getElementById('incoming_tab').click();
        </script>
    @elseif (session()->get('switch_tab') == 'friends')
        <script>
            document.getElementById('defaultOpen').click();
        </script>
    @endif
@endsection