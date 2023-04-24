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
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <header>
        <div id='droid-net'>
            <a id='logo' href="{{route('/')}}"><img src="{{ asset('images/logo.png') }}" alt='Logo'></a>
            <strong>DroidNET</strong>
        </div>

        @auth
            @php
                $image_name = isset(auth()->user()->image) ? auth()->user()->image : 'default_image.jpg';
            @endphp

        <div id='logged-in'>
            <a href="{{route('user', auth()->user()->username)}}">
                <img src='{{ asset('images/' . $image_name) }}' alt="Profile Pic">
            </a>
            <span id='logged-in-as'>Logged in as: {{ auth()->user()->username }}</span>
            <form id='logout-form' action="{{ route('logout') }}" method='POST'>
                @csrf
                <button type='submit' id='log-out'>Log Out</button>
            </form>
        </div>
        @endauth
        
    </header>
    
    <main>
        @yield('content')   
    </main>

    <footer>
        <span><strong>DroidNET &copy;, 2023 | All rights reserved</strong></span>
    </footer>
</body>
</html>