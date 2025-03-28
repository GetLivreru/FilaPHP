@extends('blog.layouts.app')

@section('title', 'Главная')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-3">
        <h1 class="text-3xl font-bold mb-6">Последние записи</h1>
        
        @foreach($posts as $post)
        <article class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
            @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
            @endif
            
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-2">
                    <a href="{{ route('post.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $post->title }}
                    </a>
                </h2>
                
                <div class="flex items-center text-gray-500 text-sm mb-4">
                    <span>{{ $post->published_at->format('d.m.Y') }}</span>
                    <span class="mx-2">•</span>
                    <a href="#" class="hover:text-blue-600">{{ $post->category->name }}</a>
                    <span class="mx-2">•</span>
                    <span>{{ $post->comments->count() }} комментариев</span>
                    <span class="mx-2">•</span>
                    <span>{{ $post->likes->count() }} лайков</span>
                </div>
                
                <div class="prose max-w-none mb-4">
                    {{ Str::limit(strip_tags($post->content), 200) }}
                </div>
                
                <a href="{{ route('post.show', $post->slug) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Читать далее
                </a>
            </div>
        </article>
        @endforeach

        {{ $posts->links() }}
    </div>

    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Категории</h2>
            <ul class="space-y-2">
                @foreach($categories as $category)
                <li>
                    <a href="#" class="text-gray-600 hover:text-blue-600">
                        {{ $category->name }} ({{ $category->posts_count }})
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection 