@extends('layouts.app')

@section('title', $post->user->name . "'s Post | Floopix")

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6 mt-6">

    <!-- Post Header -->
    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('user.profile', $post->user->id) }}" 
           class="text-indigo-700 font-semibold text-lg hover:underline">
            {{ $post->user->name }}
        </a>
        <span class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</span>
    </div>

    <!-- Post Media -->
    @if($post->type === 'photo' && $post->media_url)
        <img src="{{ asset('storage/' . $post->media_url) }}" 
             class="rounded-lg mb-4 max-h-96 mx-auto object-contain">
    @endif

    <!-- Post Content -->
    @if($post->content)
        <p class="text-gray-800 mb-4">{{ $post->content }}</p>
    @endif

    <!-- Like / Dislike Buttons -->
    <div class="flex space-x-6 mb-6">
        <form action="{{ route('posts.like', $post->id) }}" method="POST">
            @csrf
            <button type="submit" class="text-indigo-600 font-semibold hover:underline">
                ❤️ Like ({{ $post->positiveLikes->count() }})
            </button>
        </form>

        <form action="{{ route('posts.dislike', $post->id) }}" method="POST">
            @csrf
            <button type="submit" class="text-red-500 font-semibold hover:underline">
                💔 Dislike ({{ $post->negativeLikes->count() }})
            </button>
        </form>
    </div>

    <!-- Comments Section -->
    <h3 class="font-semibold text-lg mb-2">Comments</h3>

    @foreach($post->comments as $comment)
        <div class="border-t border-gray-200 pt-2 mt-2">
            <p class="text-sm text-gray-700">
                <span class="font-semibold text-indigo-700">{{ $comment->user->name }}</span>: 
                {{ $comment->content }}
            </p>
        </div>
    @endforeach

    <!-- Comment Form -->
    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-4">
        @csrf
        <textarea name="content" placeholder="Write a comment..." 
            class="w-full border rounded-md p-2 focus:ring-2 focus:ring-indigo-500"></textarea>
        <button type="submit" 
            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Comment
        </button>
    </form>
</div>
@endsection
