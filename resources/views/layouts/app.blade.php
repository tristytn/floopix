<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Floopix')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Global Navbar -->
    <nav class="bg-white shadow p-4 flex flex-wrap justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="{{ route('hot') }}" class="text-indigo-600 font-bold text-2xl">Floopix</a>
        </div>

        <!-- Search & Post Actions -->
        <form action="{{ route('search') }}" method="GET" class="flex items-center space-x-2 mb-2 sm:mb-0">
            <input type="text" name="query" placeholder="Zoek gebruikers..." 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Zoek
            </button>

            @auth
                <a href="{{ route('posts.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                    + Maak een post
                </a>
                <a href="{{ route('posts.friends') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                    👥 Vrienden Feed
                </a>
            @endauth
        </form>

        <!-- User Actions -->
        <div class="flex items-center space-x-3">
            @auth
                <a href="{{ route('user.profile', Auth::id()) }}" class="text-gray-700 font-semibold hover:underline">
                    Welkom, {{ Auth::user()->name }}
                </a>
                <a href="{{ route('logout') }}" class="text-red-600 hover:underline font-semibold">Log uit</a>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Log in</a>
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Registreer</a>
            @endguest
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-8">
        @yield('content')
    </main>

</body>
</html>
