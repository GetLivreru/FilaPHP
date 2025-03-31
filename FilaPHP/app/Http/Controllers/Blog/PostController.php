<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($slug)
    {
        $post = Post::with(['category', 'tags', 'comments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();

        return view('blog.post', compact('post'));
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|min:1|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Комментарий успешно добавлен.');
    }

    public function like(Request $request, Post $post)
    {
        $ip = $request->ip();
        
        if (!$post->likes()->where('ip_address', $ip)->exists()) {
            $post->likes()->create([
                'ip_address' => $ip
            ]);
            return response()->json(['likes' => $post->likes()->count()]);
        }

        return response()->json(['message' => 'Вы уже поставили лайк этому посту'], 403);
    }
}
