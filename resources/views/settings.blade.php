@extends('layouts/main_layout')

@section('content')
    <div id='settings-outline'>
        <h1 id='settings-header'>Settings</h1>
        <a href="{{ route('edit_profile') }}"><button class='settings-button'>Edit profile details</button></a>
        <form name='settings-form' id='settings-form' method='POST' action='{{ route('create_user') }}' enctype="multipart/form-data">
            @csrf
            
        </form>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
@endsection