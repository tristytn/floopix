@extends('layouts.app')

@section('title', 'Vriendschapsverzoeken | Floopix')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow mt-6">
    <h2 class="text-xl font-semibold mb-4">📨 Vriendschapsverzoeken</h2>

    @if($requests->isEmpty())
        <p class="text-gray-500">Geen nieuwe verzoeken.</p>
    @else
        @foreach($requests as $req)
            <div class="flex justify-between items-center border-b py-3">
                <a href="{{ route('user.profile', $req->sender->id) }}" class="text-indigo-700 font-semibold">
                    {{ $req->sender->name }}
                </a>
                <div class="flex space-x-2">
                    <form action="{{ route('friends.accept', $req->id) }}" method="POST">
                        @csrf
                        <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">✅ Accepteer</button>
                    </form>
                    <form action="{{ route('friends.decline', $req->id) }}" method="POST">
                        @csrf
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">❌ Weiger</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
