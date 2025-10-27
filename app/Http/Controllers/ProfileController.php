<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', $id)
                     ->withCount(['likes', 'comments'])
                     ->latest()
                     ->get();
        
        // Check if already friends
        $isFriend = Friend::where(function ($q) use ($id) {
                $q->where('user_id', Auth::id())->where('friend_id', $id);
            })
            ->orWhere(function ($q) use ($id) {
                $q->where('user_id', $id)->where('friend_id', Auth::id());
            })
            ->exists();

        return view('profile.show', compact('user', 'posts', 'isFriend'));
    }

    public function addFriend($id)
    {
        $friend = Friend::where('user_id', Auth::id())->where('friend_id', $id)->first();

        if (!$friend && Auth::id() != $id) {
            Friend::create([
                'user_id' => Auth::id(),
                'friend_id' => $id
            ]);
        }

        return redirect()->back()->with('success', 'Friend added!');
    }
}
