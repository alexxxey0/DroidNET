@extends('layouts/main_layout')

@section('content')
    <img id='welcome-bg' src="{{ asset('images/dark4.gif') }}">

    <p id='welcome-text'>Welcome to DroidNET!</p>
    
    <div id='buttons'>
        <button class='button log-in'>Log In</button>
        <button class='button register'>Register</button>
    </div>
@endsection