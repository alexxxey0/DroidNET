@extends('layouts/main_layout')
@section('content')

    @php
        $my_img = isset(auth()->user()->image) ? auth()->user()->image : 'default_image.jpg';
        $user_img = isset($user_info['image']) ? $user_info['image'] : 'default_image.jpg';
        $is_banned = $user_info['role'] == 'banned';
    @endphp

    <div id='chat_main'>
        <div id='top_div'>
            <img id='top_pic' src="{{ asset('images/' . $user_img) }}" alt="">
            <a href="{{ route('user', $user_info['username']) }}"><h2 id='top_name'>
                {{ $user_info['first_name'] }} {{ $user_info['last_name'] }}
                @if ($is_banned)
                    {{ __('text.banned') }}
                @endif
            </h2></a>
        </div>


        <div id='chat_window'>
            @foreach ($messages as $message)
                @php
                    $my_msg = $message['message_sender'] == auth()->user()->username;
                    $locale = Config::get('app.locale');
                    if (empty($locale)) $locale = 'en';
                    if ($locale == 'lv') {
                        $fmt = new IntlDateFormatter( "lv_LV" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
                    } elseif ($locale == 'en') {
                        $fmt = new IntlDateFormatter( "en_GB" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
                    }
                    $message_sent = ucfirst(datefmt_format($fmt, strtotime($message['created_at'])));
                @endphp

                <div class='message'>
                    @if ($my_msg)
                        <div class='msg_author'>
                            <img src="{{ asset('images/' . $my_img) }}" alt="">
                            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                            <span class='sent_at'>{{ __('text.sent_at') }}: {{ $message_sent }}</span>
                        </div>
                        <p>{!! nl2br($message['content']) !!}</p>
                    @else
                        <div class='msg_author'>
                            <img src="{{ asset('images/' . $user_img) }}" alt="">
                            <h2>{{ $user_info['first_name'] }} {{ $user_info['last_name'] }}</h2>
                            <span class='sent_at'>{{ __('text.sent_at') }}: {{ $message_sent }}</span>
                        </div>
                        <p>{!! nl2br($message['content']) !!}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class='message-buttons'>
            <form id='message_form'  name='message_form' class='new-message-form' method='POST' action='{{ route('send_message') }}'>
                @csrf
                <input type="hidden" name="message_sender" value='{{ auth()->user()->username }}'>
                <input type="hidden" name='message_receiver' value='{{ $user_info['username'] }}'>
                
                <textarea @if ($is_banned) disabled @endif id='new_message_text' class="@error ('content') is-invalid @enderror message-text" form='message_form' onkeyup=adjust(this) name="content" rows="3" placeholder="{{ __('text.your_message') }}"></textarea>
                <span>
                    @if (!$is_banned)
                        <img src="{{ asset('images/send_icon.png') }}" alt="Send">
                        <input class='send_message' type='image' name='submit' src="{{ asset('images/send_icon_active.png') }}" alt="Send" >
                    @endif
                </span>
            </form>
            @error('content')
                        <strong><p class='error-msg'>{{ $message }}</p></strong>
                        <style>
                            #message_form {
                                margin-bottom: 0;
                            }
                        </style>
            @enderror
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script src='{{ asset('js/main.js') }}'></script>
    <script>
        $(function() {
            $("#chat_window").scrollTop($("#chat_window")[0].scrollHeight);

            // Ajax request for sending a message
            $('#message_form').submit(function(event) {
                var form_id = $(this).attr('id');
                event.preventDefault();
                var formData = $(this).serializeArray();

                $.ajax({
                    url: `{{ route('send_message') }}`,
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        $('#comment_error').remove();

                        var content = response.content;
                        content = content.replace(/\n/g, "<br />");

                        var image = response.image;
                        var name = response.name;
                        var time = response.time;

                        var message = ` <div class='message'>
                                            <div class='msg_author'>
                                                <img src="` + image + `" alt="">
                                                <h2>` + name + `</h2>
                                                <span class='sent_at'>Sent at: ` + time + `</span>
                                            </div>
                                            <p>` + content + `</p>
                                        </div>`;

                        $('#chat_window').append(message);
                        $("#chat_window").scrollTop($("#chat_window")[0].scrollHeight);
                        $('#new_message_text').val('');
                        
                        
                    },

                    error: function(response) {
                        alert('error');
                    }
                });
            });

            // Refreshing the page for possible messages every 3 seconds
            var intervalId = window.setInterval(function() {
                refresh_chat();
            }, 3000);

            // Ajax request for refreshing the chat window
            function refresh_chat() {
                $.ajax({
                    url: `{{ route('refresh_messages') }}`,
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {user: `{{ $user_info['username'] }}`},
                    success: function(response) {
                        $('#chat_window').load(' #chat_window > *');
                        $('#unread_messages').load(' #unread_messages > *');

                        var chat_window = document.getElementById('chat_window');
                        var scroll = false;

                        //console.log(response.user);

                        if (Math.abs(chat_window.scrollTop - (chat_window.scrollHeight - chat_window.offsetHeight)) <= 5) {
                            scroll = true;
                        }

                        // a small delay so that element could properly render
                        setTimeout(() => {
                            if (scroll) {
                                var div = document.querySelector('#chat_window');
                                div.scrollTop = div.scrollHeight;

                            }
                        }, 1000);
                    
                    },

                    error: function(response) {
                        alert('error');
                    }
                });
            }

 
        });
    </script>
@endsection