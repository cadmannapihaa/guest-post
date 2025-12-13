@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-xl mb-4">Edit Post</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')
        @include('posts.form')
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
