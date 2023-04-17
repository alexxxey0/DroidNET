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
        <a href="{{route('/')}}"><img src="{{ asset('images/logo.png') }}"></a>
        <h1>DroidNET</h1>
    </header>
    
    <main>
        @yield('content')   
    </main>

    <footer>
        <p>DroidNET, 2023 | All rights reserved</p>
    </footer>
</body>
</html>