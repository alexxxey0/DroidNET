@extends('layouts/main_layout')

@if (count($user) == 0)
        <script>document.title = 'User not found'</script>
@endif

@section('content')
    @if (count($user) == 0)
        <h2>This user doesn't exist</h2>
    @else
        <h1>Username: {{$user[0]['username']}}</h1>
        <h1>Role: {{$user[0]['role']}}</h1>
        <h1>About me: {{$user[0]['about_me']}}</h1>
        <h1>Registered at: {{$user[0]['created_at']}}</h1>
    @endif
    
@endsection
