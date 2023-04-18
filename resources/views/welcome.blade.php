@extends('layouts/main_layout')

@section('content')
    <img id='welcome-bg' src="{{ asset('images/dark4.gif') }}">
    <img id='welcome-bg2' src="{{ asset('images/dark5.gif') }}">

    <p id='welcome-text'>Welcome to DroidNET!</p>
    
    <div id='buttons'>
        <a class='button log-in' href="{{route('login')}}">Log In</a>
        <a class='button register' href="{{route('register')}}">Register</a>
    </div>
@endsection