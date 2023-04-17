@extends('layouts/main_layout')

@if (count($user) == 0)
        <script>document.title = 'User not found'</script>
@endif

@section('content')
    <h2>Name: {{$user[0]['first_name']}} {{$user[0]['last_name']}}</h2>

    @if (count($user) == 0)
        <h2>This user doesn't exist</h2>
    @else
        @if (count($posts) == 0)
            <h2>This user doesn't have any posts</h2>
        @else
            @foreach ($posts as $post)
                <h1>{{$post['title']}}</h1>
                <h2>{{$post['content']}}</h2>

                @foreach ($comments as $comment)
                    @if ($comment['post'] == $post['id'])
                        <p>Comment by: {{$comment['author']}}</p>
                        <p>Comment text: {{$comment['content']}}</p>
                    @endif
                @endforeach
            @endforeach
        @endif
    @endif
    
@endsection
