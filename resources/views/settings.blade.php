@extends('layouts/main_layout')

@section('content')
    <div id='settings-outline'>
        <h1 id='settings-header'>{{ __('text.settings') }}</h1>
        <a href="{{ route('edit_profile') }}"><button class='settings-button'>{{ __('text.edit_profile') }}</button></a>

    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection