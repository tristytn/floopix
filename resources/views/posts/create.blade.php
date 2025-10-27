<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nieuwe Post | Floopix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-8">
    <header class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-indigo-700">📸 Nieuwe Post</h1>
        <a href="/hot" class="text-indigo-600 hover:underline">Terug naar Hot</a>
    </header>

    <div class="bg-white rounded-xl shadow-lg p-6 max-w-lg mx-auto">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-1">Tekstbericht (optioneel)</label>
                <textarea name="content" rows="4" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 p-2" placeholder="Wat wil je delen? (max 100 woorden)"></textarea>
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 font-medium mb-1">Foto uploaden (optioneel)</label>
                <input type="file" name="photo" accept="image/*" class="block w-full text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-md hover:bg-indigo-700 transition">
                Post
            </button>
        </form>
    </div>
</body>
</html>
