@extends('layouts.app')

@section('title', $user->name . ' | Profile | Floopix')

@section('content')

<div class="bg-white rounded-2xl shadow p-6 mb-6 flex items-center space-x-6">
    <div>
        <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
        <p class="text-gray-500 mb-4">Joined {{ $user->created_at->format('F Y') }}</p>

        @if ($user->id !== Auth::id())
            {{-- Already friends --}}
            @if ($isFriend)
                <div class="flex items-center space-x-3">
                    <span class="text-green-600 font-semibold">✅ Jullie zijn vrienden</span>
                    <form action="{{ route('delete.friend', $user->id) }}" method="POST">
    @csrf
    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
        ❌ Verwijder Vriend
    </button>
</form>

                </div>

            {{-- Friend request already sent --}}
            @elseif ($sentRequest)
                <span class="text-gray-500 font-semibold">⏳ Verzoek verzonden</span>

            {{-- Friend request received --}}
            @elseif ($receivedRequest)
                <div class="flex items-center space-x-3">
                    <form action="{{ route('friends.accept', $receivedRequest->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            ✅ Accepteer Verzoek
                        </button>
                    </form>
                    <form action="{{ route('friends.decline', $receivedRequest->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            ❌ Weiger Verzoek
                        </button>
                    </form>
                </div>

            {{-- No relation --}}
            @else
                <form action="{{ route('friends.request', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        ➕ Stuur Vriendverzoek
                    </button>
                </form>
            @endif

        @else
            <span class="text-gray-500">Dit is je eigen profiel.</span>
        @endif
    </div>
</div>

<section>
    <h3 class="text-lg font-semibold mb-4">📸 Posts van {{ $user->name }}</h3>

    @if ($posts->isEmpty())
        <p class="text-gray-500">Nog geen posts.</p>
    @else
        <div class="grid gap-4">
            @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post->id) }}" class="block group">
                    <div class="bg-white p-4 shadow rounded-xl hover:shadow-lg transition">
                        {{-- Post image --}}
                        @if($post->type === 'photo' && $post->media_url)
                            <img src="{{ asset('storage/' . $post->media_url) }}" 
                                 class="rounded-lg mb-3 max-h-96 max-w-full mx-auto object-contain">
                        @endif

                        {{-- Post content --}}
                        @if(!empty($post->content))
                            <p class="text-gray-800 group-hover:text-indigo-700 transition">{{ $post->content }}</p>
                        @endif

                        {{-- Likes, dislikes, comments --}}
                        <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                            <div class="flex space-x-4">
                                <span>❤️ {{ $post->positiveLikes->count() }}</span>
                                <span>💔 {{ $post->negativeLikes->count() }}</span>
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
