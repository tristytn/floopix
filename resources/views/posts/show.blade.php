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
        <form action="{{ route('posts.like', $post->id) }}" method="POST">@csrf
            <button type="submit" class="text-indigo-600 font-semibold hover:underline">
                ❤️ Like ({{ $post->positiveLikes->count() }})
            </button>
        </form>

        <form action="{{ route('posts.dislike', $post->id) }}" method="POST">@csrf
            <button type="submit" class="text-red-500 font-semibold hover:underline">
                💔 Dislike ({{ $post->negativeLikes->count() }})
            </button>
        </form>
    </div>

    <!-- Comments Section -->
    <h3 class="font-semibold text-lg mb-2">Comments</h3>

    @foreach($post->comments as $comment)
        <div class="border-t border-gray-200 pt-2 mt-2 break-words">
            <p class="text-sm text-gray-700">
                <span class="font-semibold text-indigo-700">{{ $comment->user->name }}</span>: 
                {{ $comment->content }}
            </p>
        </div>
    @endforeach

    <!-- Comment Form -->
    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-4">
        @csrf

        <!-- Error Message -->
        @error('content')
            <p class="text-red-600 mb-2 font-semibold">{{ $message }}</p>
        @enderror

        <textarea name="content" id="commentContent" placeholder="Write a comment..." 
            class="w-full border rounded-md p-2 focus:ring-2 focus:ring-indigo-500 break-words" 
            rows="4" maxlength="1000">{{ old('content') }}</textarea>

        <!-- Live counters -->
        <div class="flex justify-between mt-1 text-sm text-gray-500">
            <span id="charCount">0 / 1000 characters</span>
            <span id="wordCount">0 / 100 words</span>
        </div>

        <button type="submit" 
            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Comment
        </button>
    </form>
</div>

<!-- Live Counter Script -->
<script>
    const textarea = document.getElementById('commentContent');
    const charCount = document.getElementById('charCount');
    const wordCount = document.getElementById('wordCount');

    function updateCounts() {
        const text = textarea.value;
        const words = text.trim().split(/\s+/).filter(Boolean).length;
        const chars = text.length;

        charCount.textContent = `${chars} / 1000 characters`;
        wordCount.textContent = `${words} / 100 words`;

        charCount.style.color = chars > 1000 ? 'red' : '#6b7280';
        wordCount.style.color = words > 100 ? 'red' : '#6b7280';
    }

    textarea.addEventListener('input', updateCounts);
    window.addEventListener('load', updateCounts);
</script>
@endsection
