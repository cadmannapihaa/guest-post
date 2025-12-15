<?php

namespace App\Http\Controllers;

use App\Models\PostEditAccess;
use Illuminate\Http\Request;

class PostEditAccessController extends Controller
{
    public function grant(Request $request)
    {
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'post_id'=>'required|exists:posts,id',
            'expires_at'=>'nullable|date',
            'reason'=>'nullable|string|max:255'
        ]);

        PostEditAccess::create([
            'user_id' => $data['user_id'],
            'post_id' => $data['post_id'],
            'granted_by' => auth()->id(),
            'expires_at' => $data['expires_at'],
            'reason' => $data['reason'],
            'is_active' => true,
        ]);

        return back();
    }

    public function revoke(Request $request)
    {
        $data = $request->validate([
            'access_id'=>'required|exists:post_edit_access,id',
        ]);

        $access = PostEditAccess::findOrFail($data['access_id']);
        $access->update(['is_active'=>false]);

        return back();
    }
}
