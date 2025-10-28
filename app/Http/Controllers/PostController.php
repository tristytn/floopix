<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfanityFilter;

class PostController extends Controller
{
    protected $filter;

    public function __construct(ProfanityFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Show form to create a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a new post (photo or text) with profanity check.
     */
    public function store(Request $request)
    {
        // Handle photo posts
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|max:10240',
            ]);

            $image = $request->file('photo');
            $size = getimagesize($image);

            if ($size[0] > 5000 || $size[1] > 5000) {
                return back()->withErrors(['photo' => 'Foto is te groot (max 5000x5000 px)']);
            }
            if ($size[0] < 150 || $size[1] < 150) {
                return back()->withErrors(['photo' => 'Foto is te klein (min 150x150 px)']);
            }

            $path = $image->store('posts', 'public');

            Post::create([
                'user_id' => Auth::id(),
                'type' => 'photo',
                'media_url' => $path,
                'content' => '',
            ]);

            return redirect()->route('hot')->with('success', 'Foto succesvol gepost!');
        }

        // Validate text content
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $content = $request->input('content');

        // Check word limit
        if (str_word_count($content) > 100) {
            return back()->withErrors(['content' => 'Te lange tekst. Maximaal 100 woorden.'])->withInput();
        }

        // Profanity check
        if ($this->filter->containsBadWords($content)) {
            return back()->withErrors(['content' => 'Bericht bevat ongepaste woorden.'])->withInput();
        }

        Post::create([
            'user_id' => Auth::id(),
            'type' => 'text',
            'content' => $content,
        ]);

        return redirect()->route('hot')->with('success', 'Tekst succesvol gepost!');
    }

    /**
     * Show a single post.
     */
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'likes'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Like a post (toggle system).
     */
    public function like($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();

        $existing = $post->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->type === 'like') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'like']);
            }
        } else {
            $post->likes()->create([
                'user_id' => $userId,
                'type' => 'like',
            ]);
        }

        return redirect()->route('posts.show', $id);
    }

    /**
     * Dislike a post (toggle system).
     */
    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();

        $existing = $post->likes()->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->type === 'dislike') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'dislike']);
            }
        } else {
            $post->likes()->create([
                'user_id' => $userId,
                'type' => 'dislike',
            ]);
        }

        return redirect()->route('posts.show', $id);
    }

    /**
     * Add a comment with profanity and limits.
     */
    public function comment(Request $request, $id)
    {
        $content = $request->input('content');

        // Character limit
        if (strlen($content) > 1000) {
            return back()->withErrors(['content' => 'Comment is too long. Maximum 1000 characters allowed.'])->withInput();
        }

        // Word limit
        if (str_word_count($content) > 100) {
            return back()->withErrors(['content' => 'Comment is too long. Maximum 100 words allowed.'])->withInput();
        }

        // Profanity check
        if ($this->filter->containsBadWords($content)) {
            return back()->withErrors(['content' => 'Comment bevat ongepaste woorden.'])->withInput();
        }

        $post = Post::findOrFail($id);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        return redirect()->route('posts.show', $id)->with('success', 'Comment added!');
    }

    /**
     * Show posts by friends.
     */
    public function friendsPosts()
    {
        $friendIds = \App\Models\Friend::where('user_id', auth()->id())
            ->orWhere('friend_id', auth()->id())
            ->get()
            ->map(function($friend) {
                return $friend->user_id == auth()->id() ? $friend->friend_id : $friend->user_id;
            })->toArray();

        $posts = \App\Models\Post::with('user')
            ->withCount(['likes', 'comments'])
            ->whereIn('user_id', $friendIds)
            ->latest()
            ->get();

        return view('posts.friends', compact('posts'));
    }
}
