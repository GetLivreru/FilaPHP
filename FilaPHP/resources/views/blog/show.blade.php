<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                    
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover mb-4">
                    @endif

                    <div class="prose max-w-none mb-8">
                        {!! $post->content !!}
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('posts.like', $post) }}" method="POST" class="inline" id="like-form-{{ $post->id }}">
                                @csrf
                                <button type="submit" class="flex items-center space-x-1 {{ $post->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500' }}">
                                    <svg class="w-6 h-6" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span id="likes-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                                </button>
                            </form>
                        </div>
                        <div class="text-sm text-gray-500">
                            Опубликовано {{ $post->published_at->format('d.m.Y') }}
                        </div>
                    </div>

                    <!-- Комментарии -->
                    <div class="mt-8">
                        <h2 class="text-2xl font-bold mb-4">Комментарии</h2>

                        @auth
                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-6">
                                @csrf
                                <div>
                                    <textarea name="content" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Напишите комментарий..."></textarea>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Отправить
                                    </button>
                                </div>
                            </form>
                        @else
                            <p class="text-gray-500 mb-4">Пожалуйста, <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">войдите</a>, чтобы оставить комментарий.</p>
                        @endauth

                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">{{ $comment->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</p>
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
                                    <p class="mt-2">{{ $comment->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeForm = document.getElementById('like-form-{{ $post->id }}');
    if (likeForm) {
        likeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.likes !== undefined) {
                    document.getElementById('likes-count-{{ $post->id }}').textContent = data.likes;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});
</script>
@endpush 