@extends('layouts/main_layout')
@section('content')

    <style>
        .success-msg {
                position: fixed;
                top: 0;
                background-color: lightskyblue;
                text-align: center;
                margin-inline: auto;
                left: 0;
                right: 0;
                width: fit-content;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                border: 3px solid black;
                padding: 1%;
                z-index: 1;
            }
    </style>

    <!-- Show a message -->
    @if (session()->get('message'))
        <h1 class='success-msg'>{{ session()->get('message') }}</h1>

        <script>
            const success_msg = document.querySelector('.success-msg');
            setTimeout(() => {success_msg.style.display="none"}, 3000);
        </script>

    @endif

    @if (count($posts) > 0)
        @foreach ($posts as $post)

            @php
                // Get posters's profile picture and name
                foreach ($posters_info as $poster) {
                    //dd($poster['username'], $posters_info);
                    if ($poster['username'] == $post['author']) {
                        $poster_first_name = $poster['first_name'];
                        $poster_last_name = $poster['last_name'];
                        $poster_role = $poster['role'];
                        $poster_image_name = isset($poster['image']) ? $poster['image'] : 'default_image.jpg';
                    }
                }

                $locale = Config::get('app.locale');
                if (empty($locale)) $locale = 'en';
                if ($locale == 'lv') {
                    $fmt = new IntlDateFormatter( "lv_LV" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
                } elseif ($locale == 'en') {
                    $fmt = new IntlDateFormatter( "en_GB" ,IntlDateFormatter::FULL, IntlDateFormatter::FULL, null,IntlDateFormatter::GREGORIAN, "LLLL d, yyyy HH:mm");
                }
                $post_created = ucfirst(datefmt_format($fmt, strtotime($post['created_at'])));
                $post_updated = ucfirst(datefmt_format($fmt, strtotime($post['updated_at'])));
            @endphp

            @if ($poster_role != 'banned')
                <div class='post-buttons' id='{{ 'post-buttons' . $post['id'] }}'>

                    <div class='poster_info'>
                        <h2>{{ $poster_first_name }} {{ $poster_last_name }}</h2>
                        <a href="{{ route('user', $post['author']) }}"><img class='poster_pic' src="{{ asset('images/' . $poster_image_name) }}" alt=""></a>
                    </div>

                    <div class='post' id='{{ 'post' . $post['id'] }}'>
                        <div class='title-date'>
                            <h1>{{$post['title'] }}</h1>
                            <span>{{ __('text.published') }}: {{ $post_created }}
                                @if ($post['created_at'] != $post['updated_at'])
                                    <br>{{ __('text.edited') }}: {{ $post_updated }}
                                @endif
                            </span>
                        </div>

                        <p>{!! nl2br($post['content']) !!}</p>
                        @auth
                            <form id='{{'comment_form' . $post['id'] }}'  name='comment_form' class='new-comment-form' method='POST' action='{{ route('add_comment') }}'>
                                @csrf
                                <input type="hidden" name='post_id' value='{{ $post['id'] }}'>
                                <input type="hidden" name='img' value='{{ auth()->user()->image }}'>
                                <input type="hidden" name='name' value='{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}'>
                                <textarea id='{{'new_comment_text' . $post['id']}}' class="@error ('content', $post['id']) is-invalid @enderror comment-text" form='{{'comment_form' . $post['id'] }}' onkeyup=adjust(this) name="content" rows="3" placeholder="{{ __('text.your_comment') }}"></textarea>
                                <span>
                                    <img src="{{ asset('images/send_icon.png') }}" alt="Send">
                                    <input class='send_comment' type='image' name='submit' src="{{ asset('images/send_icon_active.png') }}" alt="Send" >
                                </span>
                                @error('content', $post['id'])
                                        <strong><p class='error-msg'>{{ $message }}</p></strong>
                                @enderror

                            </form>
                        @endauth
                        <hr>

                        @foreach ($comments as $comment)
                            @if ($comment['post'] == $post['id'])
                                @php
                                    $comment_created = ucfirst(datefmt_format($fmt, strtotime($comment['created_at'])));
                                    $comment_updated = ucfirst(datefmt_format($fmt, strtotime($comment['updated_at'])));
                                @endphp
                                <div class='comment-buttons' id='{{ 'comment-buttons' . $comment['id'] }}'>
                                    <div class="comment">
                                        
                                        @php
                                            // Get commenter's profile picture and name
                                            foreach ($commenters_info as $commenter) {
                                                if ($commenter['author'] == $comment['author']) {
                                                    $comment_image = isset($commenter['image']) ? $commenter['image'] : 'default_image.jpg';
                                                    $commenter_first_name = $commenter['first_name'];
                                                    $commenter_last_name = $commenter['last_name'];
                                                    $commenter_role = $commenter['role'];
                                                }
                                            }
                                        @endphp

                                        <a href="{{ route('user', $comment['author']) }}"><img src="{{ asset('images/' . $comment_image) }}" alt="Comment author's pic"></a>
                                        <span class='comment-name'><strong>{{ $commenter_first_name }} {{ $commenter_last_name }}</strong></span>
                                        <span class='comment-date'>
                                            {{ __('text.published') }}: {{ $comment_created }}
                                            @if ($comment['created_at'] != $comment['updated_at'])
                                                <br>{{ __('text.edited') }}: {{ $comment_updated }}
                                            @endif
                                        </span>
                                        <span></span>
                                        <span class='comment-content'> {!! nl2br($comment['content']) !!} </span>
                                    </div>

                                    @auth
                                        @if ( $comment['author'] == auth()->user()->username || $role == 'admin' || $post['author'] == auth()->user()->username || ($role == 'moderator' && !in_array($commenter_role, ['admin', 'moderator'])) )
                                            <!-- Delete comment form -->
                                            <form id='{{'delete-comment-form' . $comment['id'] }}'  name='delete-comment-form' class='delete-comment-form' method='POST' action='{{ route('delete_comment') }}'>
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name='comment_id' value='{{ $comment['id'] }}'>
                                                <div>
                                                    <img src="{{ asset('images/delete_icon.png') }}" alt="Delete">
                                                    <input class='delete-comment' type='image' name='submit' src="{{ asset('images/delete_icon_active.png') }}" alt="Delete" >
                                                </div>
                                            </form>
                                            @if ($comment['author'] == auth()->user()->username)
                                                <!-- Edit comment link -->
                                                <div class='edit-comment-link'>
                                                    <img src="{{ asset('images/edit_icon.png') }}" alt="Edit" class='edit-comment-inactive'>
                                                    <a href='{{ route('edit_comment', ['comment' => $comment['id']]) }}'><img class='edit-comment-active' type='image' name='submit' src="{{ asset('images/edit_icon_active.png') }}" alt="Edit"></a>
                                                </div>
                                            @endif
                                        @endif
                                    @endauth

                                </div>
                            @endif
                        @endforeach
                    </div>
                    @if ( $post['author'] == auth()->user()->username || auth()->user()->role == 'admin' || (auth()->user()->role == 'moderator' && !in_array($poster_role, ['admin', 'moderator'])) )
                        <!-- Delete post form -->
                        <form id='{{'delete_form' . $post['id'] }}'  name='delete-post-form' class='delete-post-form' method='POST' action='{{ route('delete_post') }}'>
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name='post_id' value='{{ $post['id'] }}'>
                            <div>
                                <img src="{{ asset('images/delete_icon.png') }}" alt="Delete">
                                <input class='delete-post' type='image' name='submit' src="{{ asset('images/delete_icon_active.png') }}" alt="Delete" >
                            </div>
                        </form>
                    @endif
                    <hr class='post_divider'>
                </div>
            @endif

        @endforeach
    @else
        <h2 class='no_feed'>{{ __('text.feed_empty') }}</h2>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script src='{{ asset('js/main.js') }}'></script>
    @php
        $locale = Config::get('app.locale');
        if (empty($locale)) $locale = 'en';
    @endphp
    <script>
        function add_delete_confirmation(button) {
            $(button).on('click', function (event) {
                event.preventDefault();

                var form = $(button).closest('form'); // Get the related form
                if ('{{ $locale }}' == 'en') {
                    if ($(this).hasClass('delete-comment')) var msg = 'Are you sure you want to delete this comment?';
                    if ($(this).hasClass('delete-post')) var msg = 'Are you sure you want to delete this post?';
                } else if ('{{$locale}}' == 'lv') {
                    if ($(this).hasClass('delete-comment')) var msg = 'Vai tiešām vēlaties dzēst šo komentāru?';
                    if ($(this).hasClass('delete-post')) var msg = 'Vai tiešām vēlaties dzēst šo ziņu?';
                }

                if (confirm(msg)) form.submit();

            })
        }
        
        $(function() {
            // Ajax request for adding new comments without refreshing the page
            $('.new-comment-form').submit(function(event) {
                var form_id = $(this).attr('id');
                event.preventDefault();
                var formData = $(this).serializeArray();

                $.ajax({
                    url: `{{ route('add_comment') }}`,
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        $('#comment_error').remove();

                        var content = response.content;
                        content = content.replace(/\n/g, "<br />");

                        var profile_link = response.profile_link;
                        var image = response.image;
                        var name = response.name;
                        var time = response.time;
                        var id = response.id;
                        var edit_link = response.edit_link;


                        var comment = ` <div class='comment-buttons' id='comment-buttons` + id + `'>
                                            <div class="comment">
                                                <a href="` + profile_link + `"><img src="` + image + `" alt="Comment author's pic"></a>
                                                <span class='comment-name'><strong>` + name + `</strong></span>
                                                <span class='comment-date'>` + time + `</span>
                                                <span></span>
                                                <span class='comment-content'>` + content + `</span>
                                            </div>
                                            <form id='delete-comment-form` + id + `'  name='delete-comment-form' class='delete-comment-form' method='POST' action='{{ route('delete_comment') }}'>
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name='comment_id' value='` + id + `'>
                                                <div>
                                                    <img src="{{ asset('images/delete_icon.png') }}" alt="Delete">
                                                    <input class='delete-comment' type='image' name='submit' src="{{ asset('images/delete_icon_active.png') }}" alt="Delete" >
                                                </div>
                                            </form>
                    
                                            <div class='edit-comment-link'>
                                                <img src="{{ asset('images/edit_icon.png') }}" alt="Edit" class='edit-comment-inactive'>
                                                <a href='` + edit_link + `'><img class='edit-comment-active' type='image' name='submit' src="{{ asset('images/edit_icon_active.png') }}" alt="Edit"></a>
                                            </div>
                                        </div>`;
                        
                        $('#post' + response.post_id + ' hr').after(comment);
                        $('textarea').val('');
                        comment_delete_ajax($('#delete-comment-form' + id)); // add an ajax event listener to the new comment
                        add_delete_confirmation($('#delete-comment-form' + id + ' .delete-comment')); // add delete confirmation for the new comment
                    },

                    error: function(response) {
                        $('#comment_error').remove(); // don't display more than 1 error message at once

                        var form = $('#' + form_id);
                        if ('{{ $locale }}' == 'en') var error = `<h2 id='comment_error'>Comment text is required!</h2>`;
                        else if ('{{ $locale }}' == 'lv') var error = `<h2 id='comment_error'>Komentāra teksts ir nepieciešams!</h2>`;
                        $(form).append(error);
                    }
                });
            });

            // Ajax request for deleting a comment
            function comment_delete_ajax(comment) {

                $(comment).on('submit', function(event) {
                    event.preventDefault();
                    var div_id = $(this).closest('.comment-buttons').attr('id');
                    var formData = $(this).serializeArray();

                    $.ajax({
                        url: `{{ route('delete_comment') }}`,
                        type: 'post',
                        data: formData,
                        success: function(response) {
                            $('#' + div_id).remove();

                            $('<h1>', {
                                class: 'success-msg',
                                text: response.message
                            }).prependTo('main');

                            const success_msg = document.querySelector('.success-msg');
                            setTimeout(() => {success_msg.style.display="none"}, 3000);
                        },

                        error: function(response) {
                            alert('error');
                        }
                    });
                });
            }

            // Ajax request for deleting a post
            $('.delete-post-form').submit(function(event) {
                var div_id = $(this).closest('.post-buttons').attr('id');
                event.preventDefault();
                var formData = $(this).serializeArray();

                $.ajax({
                    url: `{{ route('delete_post') }}`,
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        $('#' + div_id).remove();

                        $('<h1>', {
                            class: 'success-msg',
                            text: response.message
                        }).prependTo('main');

                        const success_msg = document.querySelector('.success-msg');
                        setTimeout(() => {success_msg.style.display="none"}, 3000);
                    },

                    error: function(response) {
                        alert('error');
                    }
                });
            });

            $('.delete-comment-form').each(function(index, object) {
                comment_delete_ajax(object);
            })

            $('.delete-comment').each(function(index, object) {
                add_delete_confirmation(object);
            })

            $('.delete-post').each(function(index, object) {
                add_delete_confirmation(object);
            })
            

        });
    </script>

@endsection