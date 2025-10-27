@extends('layouts.app')

@section('title', 'Friends Posts | Floopix')

@section('content')

<h2 class="text-2xl font-bold mb-6">Posts by your friends</h2>

@if ($posts->isEmpty())
    <p class="text-gray-500">Your friends haven’t posted anything yet.</p>
@else
    <div class="grid gap-4">
        @foreach ($posts as $post)
            <a href="{{ route('posts.show', $post->id) }}" class="block group">
                <div class="bg-white p-4 shadow rounded-xl hover:shadow-lg transition">
                    @if($post->type === 'photo' && $post->media_url)
                        <img src="{{ asset('storage/' . $post->media_url) }}" 
                             class="rounded-lg mb-3 max-h-96 max-w-full mx-auto object-contain">
                    @endif

                    @if(!empty($post->content))
                        <p class="text-gray-800 group-hover:text-indigo-700 transition">{{ $post->content }}</p>
                    @endif

                    <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                        <div class="flex space-x-4">
                            <span>❤️ {{ $post->likes_count }}</span>
                            <span>💬 {{ $post->comments_count }}</span>
                        </div>
                        <span>Posted on {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif

@endsection
