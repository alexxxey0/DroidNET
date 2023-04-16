<h1>Welcome to DroidNET!</h1>

@foreach ($posts as $post)
    <h2>{{$post['title']}}</h2>
    <h3>Author: {{$post['author']}}</h3>
    <p>{{$post['content']}}</p>

    <footer>Published on: {{$post['created_at']}}</footer>
    <hr>
@endforeach