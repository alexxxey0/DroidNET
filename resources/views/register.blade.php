@extends('layouts/main_layout')

@section('content')
    <div id='reg-outline'>
        <h1 id='reg-header'>Registration</h1>
        <form action="">
            <label for="username">Username</label>
            <input type="text" name="username" required>
            <label for="first_name">First name</label>
            <input type="text" name="first_name" required>
            <label for="last_name">Last name</label>
            <input type="text" name="last_name" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>
            <label for="about_me">Tell us about yourself</label>
            <textarea name="about_me" id="about_me" cols="30" rows="10"></textarea>
            <button id='reg-button' type="submit">Register!</button>
        </form>
    </div>
@endsection