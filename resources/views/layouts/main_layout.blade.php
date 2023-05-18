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
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('css/main_layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/' . $page . '.css') }}">
</head>
<body>
    <header>
        <div id='droid-net'>
            @auth
                <a id='logo' href="{{route('user', ['username' => auth()->user()->username])}}"><img src="{{ asset('images/logo.png') }}" alt='Logo'></a>
            @else
                <a id='logo' href="{{route('/')}}"><img src="{{ asset('images/logo.png') }}" alt='Logo'></a>
            @endauth
            <strong>DroidNET</strong>
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
                <span id='logged-in-as'>Logged in as: {{ auth()->user()->username }}</span>
                <form id='logout-form' action="{{ route('logout') }}" method='POST'>
                    @csrf
                    <button type='submit' id='log-out'>Log Out</button>
                </form>
            </div>
        @endauth
        
    </header>
    
    <main>
        @auth
            <div class="nav">
                <nav>
                    <a href="{{ route('user', auth()->user()->username) }}"><strong>My Page</strong></a>
                    <a href="{{ route('show_feed') }}"><strong>Feed</strong></a>
                    <a id='friends' href="{{ route('friends', auth()->user()->username) }}">
                        <strong>Friends</strong>
                        @if (isset($received_requests_count) && $received_requests_count > 0)
                            <div>{{ $received_requests_count }}</div>
                        @endif
                    </a>
                    <a href="{{ route('search_page') }}"><strong>Search</strong></a>
                    <a href="#"><strong>Messages</strong></a>
                    <a href="{{ route('settings') }}"><strong>Settings</strong></a>
                </nav>
            </div>
        @endauth
        <div class='content'>
            @yield('content')   
        </div>
    </main>

    <footer>
        <span><strong>DroidNET &copy;, 2023 | All rights reserved</strong></span>
    </footer>
</body>
</html>