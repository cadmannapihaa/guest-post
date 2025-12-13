@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg py-8">
    <h1 class="text-2xl mb-6">Login</h1>

    <form method="POST" action="{{ route('login.perform') }}">
        @csrf

        <x-input name="email" label="Email" type="email" />
        <x-input name="password" label="Password" type="password" />

        <label class="inline-flex items-center my-4">
            <input type="checkbox" name="remember" class="form-checkbox">
            <span class="ml-2">Remember Me</span>
        </label>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
@endsection
