<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $likes = $post->likes()->count();
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
            $likes = $post->likes()->count();
        }

        return response()->json(['likes' => $likes]);
    }
} 