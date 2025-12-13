<div class="space-y-4">
    <x-input name="title" label="Title" :value="$post->title ?? old('title')" />
    <x-input name="slug" label="Slug" :value="$post->slug ?? old('slug')" />

    <div>
        <label class="block text-sm font-medium text-gray-700">Content</label>
        <textarea name="content" rows="8" class="input">{{ $post->content ?? old('content') }}</textarea>
    </div>

    <label class="flex items-center">
        <input type="checkbox" name="is_published" value="1" {{ ($post->is_published ?? false) ? 'checked' : '' }}>
        <span class="ml-2">Published</span>
    </label>

    <label class="flex items-center">
        <input type="checkbox" name="allow_comments" value="1" {{ ($post->allow_comments ?? true) ? 'checked' : '' }}>
        <span class="ml-2">Allow Comments</span>
    </label>
</div>
