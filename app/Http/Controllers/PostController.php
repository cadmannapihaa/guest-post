<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostStoreRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Post::create($data);
        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('edit', $post); // policy
        return view('posts.edit', compact('post'));
    }

    public function update(PostStoreRequest $request, Post $post)
    {
        $this->authorize('edit', $post);

        $post->update($request->validated());
        return redirect()->route('posts.show', $post);
    }

    public function destroy(Post $post){
        $this->authorize('edit', $post);
        $post->delete();
        return redirect()->route('posts.index');
    }
}
