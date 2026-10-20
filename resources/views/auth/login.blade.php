@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
    <form method="POST" action="{{ route('login') }}" class="card auth">
        @csrf
        <h1>Masuk</h1>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        @error('email')
        <p class="err">{{ $message }}</p>@enderror
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
        <label style="display:flex;gap:8px;align-items:center"><input type="checkbox" name="remember"
                style="width:auto;min-height:0"> Ingat saya</label>
        <button class="btn" style="width:100%;margin-top:16px">Masuk</button>
    </form>
@endsection