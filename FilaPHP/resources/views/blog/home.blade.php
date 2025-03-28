@extends('blog.layouts.app')

@section('title', 'Главная')

@section('content')
<div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Основная колонка с постами -->
        <div class="md:col-span-3">
            <h1 class="text-3xl font-bold mb-6">Последние записи</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center text-gray-500 text-sm mb-2">
                            <span>{{ $post->published_at->format('d.m.Y') }}</span>
                            <span class="mx-2">•</span>
                            <a href="#" class="hover:text-blue-600">{{ $post->category->name }}</a>
                        </div>

                        <h2 class="text-xl font-semibold mb-2">
                            <a href="{{ route('post.show', $post->slug) }}" class="text-gray-800 hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h2>
                        
                        <div class="prose prose-sm max-w-none mb-4 text-gray-600">
                            {{ Str::limit(strip_tags($post->content), 150) }}
                        </div>

                        @if($post->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($post->tags as $tag)
                            <a href="#" class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-sm hover:bg-gray-200">
                                {{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ $post->comments->count() }}
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $post->likes->count() }}
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Боковая колонка -->
        <div class="md:col-span-1">
            <!-- Категории -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Категории</h2>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li>
                        <a href="#" class="text-gray-600 hover:text-blue-600 flex items-center justify-between">
                            <span>{{ $category->name }}</span>
                            <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-full text-sm">
                                {{ $category->posts_count }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Популярные теги -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Популярные теги</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($posts->pluck('tags')->flatten()->unique('id')->take(10) as $tag)
                    <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm hover:bg-gray-200">
                        {{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 