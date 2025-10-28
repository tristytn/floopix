@extends('layouts.app')

@section('title', 'Search Results | Floopix')

@section('content')
<div class="max-w-3xl mx-auto mt-6 bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Zoekresultaten voor “{{ $query }}”</h2>

    @if($users->isEmpty())
        <p class="text-gray-500">Geen gebruikers gevonden.</p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach($users as $user)
                <li class="py-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                    </div>
                    <a href="{{ route('user.profile', $user->id) }}" 
                       class="text-indigo-600 hover:underline">
                        View Profile
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
