@extends('layouts.app')

@section('title', $user->name . ' | Profile | Floopix')

@section('content')

<div class="bg-white rounded-2xl shadow p-6 mb-6 flex items-center space-x-6">
    <div>
        <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
        <p class="text-gray-500 mb-4">Joined {{ $user->created_at->format('F Y') }}</p>

        @if ($user->id !== Auth::id())
            @if ($isFriend)
                <span class="text-green-600 font-semibold">✅ You are friends</span>
            @else
                <form action="{{ route('add.friend', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        ➕ Add Friend
                    </button>
                </form>
            @endif
        @else
            <span class="text-gray-500">This is your own profile.</span>
        @endif
    </div>
</div>

<section>
    <h3 class="text-lg font-semibold mb-4">📸 Posts by {{ $user->name }}</h3>

    @if ($posts->isEmpty())
        <p class="text-gray-500">No posts yet.</p>
    @else
        <div class="grid gap-4">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post->id) }}" class="block group">
                    <div class="bg-white p-4 shadow rounded-xl hover:shadow-lg transition">
                        <!-- Image -->
                        @if($post->type === 'photo' && $post->media_url)
                            <img src="{{ asset('storage/' . $post->media_url) }}" 
                                 class="rounded-lg mb-3 max-h-96 max-w-full mx-auto object-contain">
                        @endif

                        <!-- Post content -->
                        @if(!empty($post->content))
                            <p class="text-gray-800 group-hover:text-indigo-700 transition">{{ $post->content }}</p>
                        @endif

                        <!-- Likes, Comments, and Date -->
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
</section>

@endsection
