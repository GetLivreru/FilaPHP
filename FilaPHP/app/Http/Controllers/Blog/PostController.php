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
            $query->where('is_approved', true)->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();

        return view('blog.post', compact('post'));
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|min:10',
        ]);

        $post->comments()->create($validated);

        return back()->with('success', 'Комментарий успешно добавлен и будет опубликован после проверки.');
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
