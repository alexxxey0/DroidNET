@extends('layouts/main_layout')
@section('content')
    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'moderator')
        <form id='edit_ban_form'  method='POST' action='{{ route('ban_user') }}'>
            @csrf
            @method('PUT')
            <input name='username' type="text" required placeholder="{{ __('text.enter_username') }}">

            <select required name="ban_reason" id="ban_reason">
                <option value="" disabled selected>{{ __('text.select_reason') }}</option>
                <option value="harassment">{{ __('text.harassment')}}</option>
                <option value="threats">{{ __('text.threats') }}</option>
                <option value="spamming">{{ __('text.spamming') }}</option>
                <option value="fake_accounts">{{ __('text.fake_accounts') }}</option>
                <option value="illegal">{{ __('text.illegal') }}</option>
                <option value="other">{{ __('text.other') }}</option>
            </select>
            <button type="submit">{{ __('text.appoint_ban')}}</button>
        </form>

        <div id='bans'>
            @foreach ($bans as $ban)
                @php
                    $ban_image = isset($ban['image']) ? $ban['image'] : 'default_image.jpg';
                @endphp
                <div class="ban">
                    <a href="{{ route('user', $ban['username']) }}"><img src="{{ asset('images/'. $ban_image) }}" alt="banerator profile picture"></a>
                    <div>
                        <h2>{{ $ban['first_name'] }} {{ $ban['last_name'] }}</h2>
                        <h3>{{ $ban['username'] }}</h3>
                    </div>
                    <form class='remove_ban_form'  method='POST' action="{{ route('unban_user') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name='username' value='{{ $ban['username'] }}'>
                        <button class='remove_ban'>{{ __('text.remove_ban') }}</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <h2>{{ __('text.page_not_allowed') }}</h2>
    @endif
@endsection