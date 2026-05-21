<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class PostController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

   return Post::with(['user', 'comments.user'])
    ->latest()
    ->where('user_id', auth()->id())
    ->when($search, function ($query, $search) {
        $query->where('title', 'like', "%{$search}%");
    })
    ->latest()
    ->paginate(5);
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:255',
        'body' => 'nullable',
    ]);

    return Post::create([
        'title' => $validated['title'],
        'body' => $validated['body'] ?? null,
        'user_id' => auth()->id(),
    ]);
}

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
    abort(403);
}
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return $post;
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
    abort(403);
}
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
public function storeComment(Request $request, Post $post)
{
    if ($post->user_id !== auth()->id()) {
        abort(403);
    }

    $validated = $request->validate([
        'body' => 'required',
    ]);

    $comment = Comment::create([
        'body' => $validated['body'],
        'post_id' => $post->id,
        'user_id' => auth()->id(),
    ]);

    return $comment->load('user');
}
public function destroyComment(Comment $comment)
{
    if ($comment->user_id !== auth()->id()) {
        abort(403);
    }

    $comment->delete();

    return response()->json([
        'message' => 'Comment deleted successfully'
    ]);
 }
}