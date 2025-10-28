<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\FriendRequest;
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

        // Are we friends?
        $isFriend = FriendRequest::where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $id)->where('status', 'accepted');
            })->orWhere(function ($q) use ($id) {
                $q->where('sender_id', $id)->where('receiver_id', Auth::id())->where('status', 'accepted');
            })->exists();

        // Check for sent and received pending requests
        $sentRequest = FriendRequest::where('sender_id', Auth::id())
                        ->where('receiver_id', $id)
                        ->where('status', 'pending')
                        ->first();

        $receivedRequest = FriendRequest::where('sender_id', $id)
                            ->where('receiver_id', Auth::id())
                            ->where('status', 'pending')
                            ->first();

        return view('profile.show', compact(
            'user', 'posts', 'isFriend', 'sentRequest', 'receivedRequest'
        ));
    }

    /**
     * Remove a friend (delete accepted friend request)
     */
    public function removeFriend($id)
    {
        $friendRequest = FriendRequest::where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $id);
            })->orWhere(function ($q) use ($id) {
                $q->where('sender_id', $id)->where('receiver_id', Auth::id());
            })
            ->where('status', 'accepted')
            ->first();

        if ($friendRequest) {
            $friendRequest->delete();
        }

        return back()->with('success', 'Vriend verwijderd!');
    }
}
