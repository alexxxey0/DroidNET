<!-- if user is authenticated redirect him to his page -->
@auth
        <script>window.location = '{{ route('user', auth()->user()->username) }}';</script>
@endauth

@extends('layouts/main_layout')

@section('content')
    <p id='welcome-text'>Welcome to DroidNET!</p>
    
    <div id='buttons'>
        <a class='button log-in' href="{{route('login')}}">Log In</a>
        <a class='button register' href="{{route('register')}}">Register</a>
    </div>
@endsection