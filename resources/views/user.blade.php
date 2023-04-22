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
                width: 40%;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                border: 3px solid black;
                padding: 1%;
            }
    </style>

    @if (session()->get('register_success'))

        <!-- Show a "registration successful" message -->
        <h1 class='success-msg'>Registration successful!</h1>

        <script>
            const success_msg = document.querySelector('.success-msg');
            setTimeout(() => {success_msg.style.display="none"}, 3000);
        </script>

    @endif
    
    <!-- Show a "post created successfully" message -->
    @if (session()->get('post_success'))
        <h1 class='success-msg'>Post created successfully!</h1>

        <script>
            const success_msg = document.querySelector('.success-msg');
            setTimeout(() => {success_msg.style.display="none"}, 3000);
        </script>

    @endif

    @if (count($user) == 0)
        <h2>This user doesn't exist</h2>
        <script>document.title = 'User not found'</script>
    @else
        @auth
            @php
                $my_page = auth()->user()->username == $user[0]['username'];
            @endphp
        @endauth

        @php
            $image_name = isset($user[0]['image']) ? $user[0]['image'] : 'default_image.jpg';
        @endphp
        
        <div id='user-info'>
            <span><strong>{{$user[0]['first_name']}} {{$user[0]['last_name']}}</strong></span>
            <img src="{{ asset('images/' . $image_name) }}" alt="Profile Pic">
            <p>{!! nl2br($user[0]['about_me']) !!}</p>
        </div>

        @auth     
            @if ($my_page)
                <div class='new-post'>
                    <a href="{{ route('new_post') }}"><button>Create a new post!</button></a>
                </div>
            @endif
        @endauth

        @if (count($posts) == 0)

            @if ($my_page)
                <h1 id='no-posts'>You have no posts yet!</h1>
            @else
                <h1 id='no-posts'>This user has no posts yet!</h2>
            @endif
            
        @else
            @foreach ($posts as $post)
                <div class='post' id='{{ 'post' . $post['id'] }}'>
                    <div class='title-date'>
                        <h1>{{$post['title'] }}</h1>
                        <span>Published on: {{ date("M jS, Y G:i", strtotime($post['created_at'])) }}</span>
                    </div>

                    <p>{!! nl2br($post['content']) !!}</p>
                    @auth
                        <form id='{{'comment_form' . $post['id'] }}'  name='comment_form' class='new-comment-form' method='POST' action='{{ route('add_comment') }}'>
                            @csrf
                            <input type="text" name='post_id' value='{{ $post['id'] }}'>
                            <textarea id='{{'new_comment_text' . $post['id']}}' class="@error ('content', $post['id']) is-invalid @enderror comment-text" form='{{'comment_form' . $post['id'] }}' onkeyup=adjust(this) name="content" rows="3" placeholder="Your comment"></textarea>
                            <span>
                                <img src="{{ asset('images/send_icon.png') }}" alt="Send" >
                                <input id='send_comment' type='image' name='submit' src="{{ asset('images/send_icon_active.png') }}" alt="Send" >
                            </span>
                            @error('content', $post['id'])
                                    <strong><p class='error-msg'>{{ $message }}</p></strong>
                            @enderror

                        </form>
                    @endauth
                    <hr>

                    @foreach ($comments as $comment)
                        @if ($comment['post'] == $post['id'])
                            <div class="comment">
                                
                                @php
                                    // Get commenter's profile picture and name
                                    foreach ($commenters_info as $commenter) {
                                        if ($commenter['author'] == $comment['author']) {
                                            $comment_image = isset($commenter['image']) ? $commenter['image'] : 'default_image.jpg';
                                            $commenter_first_name = $commenter['first_name'];
                                            $commenter_last_name = $commenter['last_name'];
                                        }
                                    }
                                @endphp

                                <a href="{{ route('user', $comment['author']) }}"><img src="{{ asset('images/' . $comment_image) }}" alt="Comment author's pic"></a>
                                <span class='comment-name'><strong>{{ $commenter_first_name }} {{ $commenter_last_name }}</strong></span>
                                <span class='comment-date'>{{ date("M jS, Y G:i", strtotime($comment['created_at'])) }}</span>
                                <span></span>
                                <span class='comment-content'> {!! nl2br($comment['content']) !!} </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @endif
    @endif
    <script src="{{ asset('js/textarea_adjust.js') }}"></script>
    
@endsection
