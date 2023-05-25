<!DOCTYPE html>
<html lang="en">
<head>
    @php
        // These headers will force the browser, and proxies if any, not to cache the page and force a new request to the server for that page
        header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
        header("Cache-Control: no-store, no-cache, must-revalidate"); //HTTP/1.1
        
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('css/main_layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/' . $page . '.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/icon1.png') }}" type="image/x-icon">
</head>
<body>
    <header>
        <div id='droid-net'>
            <div id='logo_img'>
                @auth
                    <a id='logo' href="{{route('user', ['username' => auth()->user()->username])}}"><img src="{{ asset('images/logo.png') }}" alt='Logo'></a>
                @else
                    <a id='logo' href="{{route('/')}}"><img src="{{ asset('images/logo.png') }}" alt='Logo'></a>
                @endauth
                <strong>DroidNET</strong>
                <div id="flags">
                    <a title='English' class='flag uk' href="{{route('lang', 'en')}}"><img src="{{ asset('images/uk.png') }}" alt=""></a>
                    <a title='Latvian' class='flag lv' href="{{route('lang', 'lv')}}"><img src="{{ asset('images/latvia.png') }}" alt=""></a>
                </div>
            </div>

            @guest
                <a id='search_top_link' href="{{ route('search_page') }}">{{ __('text.search_for_people') }} &#128270;</a>
            @endguest
        </div>

        

        @auth
            @php
                $image_name = isset(auth()->user()->image) ? auth()->user()->image : 'default_image.jpg';
            @endphp

            <div id='logged-in'>
                @if ($role == 'admin')
                    <a href="{{route('user', auth()->user()->username)}}">
                        <img class='admin-profile-pic' src='{{ asset('images/' . $image_name) }}' alt="Profile Pic">
                    </a>
                @else
                    <a href="{{route('user', auth()->user()->username)}}">
                        <img src='{{ asset('images/' . $image_name) }}' alt="Profile Pic">
                    </a>
                @endif
                <span id='logged-in-as'>{{ __('text.logged_in_as') }}: {{ auth()->user()->username }}</span>
                <form id='logout-form' action="{{ route('logout') }}" method='POST'>
                    @csrf
                    <button type='submit' id='log-out'>{{ __('text.log_out') }}</button>
                </form>
            </div>
        @endauth
        
    </header>
    
    <main>
        @auth
            <div class="nav">
                <nav>
                    <a href="{{ route('user', auth()->user()->username) }}"><strong>{{ __('text.my_page') }}</strong></a>
                    <a href="{{ route('show_feed') }}"><strong>{{ __('text.feed') }}</strong></a>
                    <a id='friends' href="{{ route('friends', auth()->user()->username) }}">
                        <strong>{{ __('text.friends') }}</strong>
                        @if (isset($received_requests_count) && $received_requests_count > 0)
                            <div>{{ $received_requests_count }}</div>
                        @endif
                    </a>
                    <a href="{{ route('search_page') }}"><strong>{{ __('text.search') }}</strong></a>
                    <a id='unread_messages' href="{{ route('show_chats') }}">
                        <strong>{{ __('text.chats') }}</strong>
                        @if (isset($unread_messages_count) && $unread_messages_count > 0)
                            <div>{{ $unread_messages_count }}</div>
                        @endif
                    </a>
                    <a href="{{ route('settings') }}"><strong>{{ __('text.settings') }}</strong></a>
                </nav>
            </div>
        @endauth
        <div class='content'>
            @yield('content')   
        </div>
    </main>

    <footer>
        <span><strong>DroidNET &copy;, 2023 | {{ __('text.reserved') }}</strong></span>
    </footer>
</body>
</html>