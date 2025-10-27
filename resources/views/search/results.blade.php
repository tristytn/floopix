<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | Floopix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="p-4 bg-white shadow flex items-center justify-between">
        <a href="/hot" class="text-indigo-600 font-bold text-lg">Floopix</a>

        <form action="{{ route('search') }}" method="GET" class="flex items-center space-x-2">
            <input type="text" name="query" placeholder="Search users..." value="{{ $query ?? '' }}"
                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">Search</button>
        </form>
    </div>

    <div class="max-w-3xl mx-auto mt-10 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Search Results for “{{ $query }}”</h2>

        @if($users->isEmpty())
            <p class="text-gray-500">No users found.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        </div>
                        <a href="{{ url('/user/' . $user->id) }}" class="text-indigo-600 hover:underline">
                            View Profile
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

</body>
</html>
