<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->paginate(10);
        return view('comments.index', compact('post', 'comments'));
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_approved' => auth()->user()->can('approve comments'),
        ]);

        return back();
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate(['content'=>'required|string']);

        $comment->update(['content' => $request->content]);

        return redirect()->route('posts.show', $comment->post_id);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        return back();
    }
}
