@extends('layouts.app')

@section('title', 'Friends Posts | Floopix')

@section('content')
    <main class="grid gap-4 max-w-3xl mx-auto">
        @if($posts->isEmpty())
            <p class="text-center text-gray-500">Your friends haven’t posted anything yet.</p>
        @else
            @foreach($posts as $post)
                <div class="bg-white shadow rounded-2xl p-4">
                    <!-- User info and post stats -->
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <!-- Clickable username -->
                            <a href="{{ route('user.profile', $post->user->id) }}" 
                               class="font-semibold text-lg text-indigo-700 hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>

                        <p class="text-sm text-gray-500">
                            <span>❤️ {{ $post->positiveLikes->count() }}</span> ·
                                <span>💔 {{ $post->negativeLikes->count() }}</span> · 
                                💬 {{ $post->comments_count }}
                        </p>
                    </div>

                    <!-- Clickable post -->
                    <a href="{{ route('posts.show', $post->id) }}">
                        @if($post->type === 'photo' && $post->media_url)
                            <img src="{{ asset('storage/' . $post->media_url) }}" 
                                 alt="Post Image" 
                                 class="rounded-lg mb-3 max-h-96 mx-auto hover:opacity-90 transition">
                        @endif

                        @if(!empty($post->content))
                            <p class="text-gray-700 hover:text-indigo-700 transition">{{ $post->content }}</p>
                        @endif
                    </a>
                </div>
            @endforeach
        @endif
    </main>
@endsection
