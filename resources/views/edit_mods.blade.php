@extends('layouts/main_layout')
@section('content')
    <form id='edit_mod_form' action="{{ route('add_mod') }}" method='POST'>
        @csrf
        @method('PUT')
        <input name='username' type="text" required placeholder="{{ __('text.enter_username') }}">
        <button type="submit">{{ __('text.appoint_mod')}}</button>
    </form>

    <div id='mods'>
        @foreach ($mods as $mod)
            @php
                $mod_image = isset($mod['image']) ? $mod['image'] : 'default_image.jpg';
            @endphp
            <div class="mod">
                <a href="{{ route('user', $mod['username']) }}"><img src="{{ asset('images/'. $mod_image) }}" alt="Moderator profile picture"></a>
                <div>
                    <h2>{{ $mod['first_name'] }} {{ $mod['last_name'] }}</h2>
                    <h3>{{ $mod['username'] }}</h3>
                </div>
                <form class='remove_mod_form' action="{{ route('remove_mod') }}" method='POST'>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name='username' value='{{ $mod['username'] }}'>
                    <button class='remove_mod'>{{ __('text.remove_mod') }}</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection