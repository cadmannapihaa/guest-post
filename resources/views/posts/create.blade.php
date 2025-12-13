@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-xl mb-4">Create Post</h1>

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        @include('posts.form')
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
