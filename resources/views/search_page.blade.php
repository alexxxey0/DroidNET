@extends('layouts/main_layout')

@section('content')
    <form id='search_form' action="{{ route('show_search_results') }}">
        <input minlength="3" required placeholder="{{ __('text.enter_name') }}" type="text" name='search_input'>
        <button type="submit">{{ __('text.search') }}</button>
    </form>

    @if (isset($search_results))

        @if (count($search_results) > 0)
            @foreach ($search_results as $user)
                <div class="user">
                    @php
                        $user_image = isset($user['image']) ? $user['image'] : 'default_image.jpg';
                    @endphp
                    <a href="{{ route('user', $user['username']) }}"><img src="{{ asset('images/' . $user_image) }}" alt="User's pic"></a>
                    <a href="{{ route('user', $user['username']) }}" class="name_link"><span class='user-name'><strong>{{ $user['first_name'] }} {{ $user['last_name'] }}</strong></span></a>
                </div>
            @endforeach
        @else
            <h1 id='no_results'>{{ __('text.no_results') }}</h1>
        @endif
    @endif

@endsection