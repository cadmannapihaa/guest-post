@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl">Posts</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
    </div>

    @foreach ($posts as $post)
        <div class="border p-4 mb-3">
            <h2 class="text-lg font-bold">{{ $post->title }}</h2>
            <p>By {{ $post->author->name }}</p>
            <a href="{{ route('posts.show', $post) }}" class="text-blue-600">View</a>
        </div>
    @endforeach

    {{ $posts->links() }}
</div>
@endsection
