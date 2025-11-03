<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
    
class HotController extends Controller
{
    public function index()
    {
        // One hour ago
        $oneHourAgo = Carbon::now()->subHour();

        // Fetch posts created in the last hour, ranked by likes + comments
        $hotPosts = Post::with('user')
            ->withCount(['likes', 'comments'])
            ->where('created_at', '>=', $oneHourAgo)
            ->orderByRaw('(likes_count * 2 + comments_count) DESC') // weighted score
            ->take(20)
            ->get();

        return view('hot', compact('hotPosts'));
    }
}
