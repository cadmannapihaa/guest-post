<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . ($this->post?->id ?? 'NULL'),
            'excerpt' => 'nullable|string|max:512',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'allow_comments' => 'boolean',
        ];
    }
}
