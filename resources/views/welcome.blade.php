<!-- if user is authenticated redirect him to his page -->
@auth
        <script>window.location = '{{ route('user', auth()->user()->username) }}';</script>
@endauth

@extends('layouts/main_layout')

@section('content')
    <p id='welcome-text'>{{ __('text.welcome') }}</p>
    
    <div id='buttons'>
        <a class='button log-in' href="{{route('login')}}">{{ __('text.login') }}</a>
        <a class='button register' href="{{route('register')}}">{{ __('text.register') }}</a>
    </div>
@endsection