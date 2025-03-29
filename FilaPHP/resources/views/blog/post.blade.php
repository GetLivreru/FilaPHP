@extends('blog.layouts.app')

@section('title', $post->title)

@section('content')
<article class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($post->featured_image)
    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
    @endif
    
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        
        <div class="flex items-center text-gray-500 text-sm mb-6">
            <span>{{ $post->published_at->format('d.m.Y') }}</span>
            <span class="mx-2">•</span>
            <a href="#" class="hover:text-blue-600">{{ $post->category->name }}</a>
            <span class="mx-2">•</span>
            <span>{{ $post->comments->count() }} комментариев</span>
            <span class="mx-2">•</span>
            <span id="likes-count">{{ $post->likes->count() }}</span> лайков
            @auth
                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="ml-2 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="ml-2 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endauth
        </div>
        
        <div class="prose max-w-none mb-8">
            {!! $post->content !!}
        </div>
        
        @if($post->tags->count() > 0)
        <div class="flex items-center space-x-2 mb-8">
            <span class="text-gray-600">Теги:</span>
            @foreach($post->tags as $tag)
            <a href="#" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full hover:bg-gray-200">
                {{ $tag->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>
</article>

<div class="mt-8">
    <h2 class="text-2xl font-bold mb-6">Комментарии ({{ $post->comments->count() }})</h2>
    
    @auth
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">Оставить комментарий</h3>
            <form action="{{ route('posts.comments.store', $post) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 mb-2">Комментарий</label>
                    <textarea name="content" id="content" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Отправить
                </button>
            </form>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <p class="text-gray-600">Пожалуйста, <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">войдите</a>, чтобы оставить комментарий.</p>
        </div>
    @endauth
    
    <div class="space-y-6">
        @foreach($post->comments as $comment)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-semibold">{{ $comment->user->name }}</h4>
                    <span class="text-gray-500 text-sm">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                </div>
                @if(auth()->id() === $comment->user_id)
                    <form action="{{ route('posts.comments.destroy', [$post, $comment]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
            <p class="text-gray-700">{{ $comment->content }}</p>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function likePost() {
    fetch('{{ route('posts.like', $post) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.likes) {
            document.getElementById('likes-count').textContent = data.likes;
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection 