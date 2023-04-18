<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
    <link rel="stylesheet" href="{{ asset('css/main_layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/' . $page . '.css') }}">
</head>
<body>
    <header>
        <a id='logo' href="{{route('/')}}"><img src="{{ asset('images/logo.png') }}"></a>
        <a id='home-link' href="{{route('/')}}"><strong>DroidNET</strong></a>

        @auth
            @php
                $image_name = isset(auth()->user()->image) ? auth()->user()->image : 'default_image.jpg';
            @endphp
            <a id='top-pic-link' href="{{route('user', auth()->user()->username)}}">
                <img id='top-pic' src='{{ asset('images/' . $image_name) }}' alt="Profile Pic">
            </a>

            <div id='logged-in'>
                <span id='logged-in-as'>Logged in as: {{ auth()->user()->username }}</span>
                <form id='logout-form' action="{{ route('/logout') }}" method='POST'>
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
        <p><strong>DroidNET &copy;, 2023 | All rights reserved</strong></p>
    </footer>
</body>
</html>