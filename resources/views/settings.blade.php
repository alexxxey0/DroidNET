@extends('layouts/main_layout')

@section('content')
    <div id='settings-outline'>
        <h1 id='settings-header'>{{ __('text.settings') }}</h1>
        <a href="{{ route('edit_profile') }}"><button class='settings-button'>{{ __('text.edit_profile') }}</button></a>
        @if (auth()->user()->role == 'admin')
            <a href="{{ route('edit_mods') }}"><button class='settings-button'>{{ __('text.edit_mods') }}</button></a>
            <a href="{{ route('banned_users') }}"><button class='settings-button'>{{ __('text.banned_users') }}</button></a>
        @elseif (auth()->user()->role == 'moderator')
            <a href="{{ route('banned_users') }}"><button class='settings-button'>{{ __('text.banned_users') }}</button></a>
        @endif
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection