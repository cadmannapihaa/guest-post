@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <h1 class="text-2xl mb-6">Register</h1>

    <form method="POST" action="{{ route('register.perform') }}">
        @csrf

        <x-input name="name" label="Name" />
        <x-input name="username" label="Username" />
        <x-input name="email" label="Email" type="email" />
        <x-input name="password" label="Password" type="password" />
        <x-input name="password_confirmation" label="Confirm Password" type="password" />

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
