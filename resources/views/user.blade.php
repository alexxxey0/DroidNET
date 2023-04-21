@extends('layouts/main_layout')

<script>
    function adjustHeight(el) {
        el.style.height = (el.scrollHeight > el.clientHeight) ? (el.scrollHeight) + "px" : "3%";
    } 
</script>

@if (count($user) == 0)
        <script>document.title = 'User not found'</script>
@endif

@section('content')

    @if (count($user) == 0)
        <h2>This user doesn't exist</h2>
    @else
            @php $image_name = isset($user[0]['image']) ? $user[0]['image'] : 'default_image.jpg'; @endphp
            

            
            <div id='user-info'>
                <span><strong>{{$user[0]['first_name']}} {{$user[0]['last_name']}}</strong></span>
                <img src="{{ asset('images/' . $image_name) }}" alt="Profile Pic">
                <p>{!! nl2br($user[0]['about_me']) !!}</p>
            </div>

            @if (count($posts) == 0)
                <h1 id='no-posts'>This user hasn't made any posts yet!</h2>
            @else
                @foreach ($posts as $post)
                    <div class='post'>
                        <div class='title-date'>
                            <h1>{{$post['title'] }}</h1>
                            <span>Published on: {{ date("M jS, Y G:i", strtotime($post['created_at'])) }}</span>
                        </div>

                        <h2>{!! nl2br($post['content']) !!}</h2>
                        
                        <form class='new-comment-form' method='POST'>
                            <textarea onkeyup="adjustHeight(this)" name="comment-text" class="comment-text" rows="3" placeholder="Your comment"></textarea>
                            <span>
                                <img src="{{ asset('images/send_icon.png') }}" alt="Send" >
                                <input type='image' name='submit' src="{{ asset('images/send_icon_active.png') }}" alt="Send" >
                            </span>

                        </form>
                        @foreach ($comments as $comment)
                            @if ($comment['post'] == $post['id'])
                                <div class="comment">
                                    
                                    @php
                                        // Get commenter's profile picture
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
                                    <span></span>
                                    <span class='comment-content'> {!! nl2br($comment['content']) !!} </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            @endif
    @endif
    
@endsection
