<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

//* Tag controller 


class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(20);
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:100|unique:tags,name',
            'slug'=>'required|string|max:100|unique:tags,slug',
        ]);

        Tag::create($request->only('name','slug'));
        return redirect()->route('tags.index');
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name'=>'required|string|max:100|unique:tags,name,'.$tag->id,
            'slug'=>'required|string|max:100|unique:tags,slug,'.$tag->id,
        ]);

        $tag->update($data);
        return redirect()->route('tags.index');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back();
    }
}
