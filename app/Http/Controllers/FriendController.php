<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // Send a friend request
    public function sendRequest($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'You cannot send a request to yourself.');
        }

        $existing = FriendRequest::where('sender_id', Auth::id())
                        ->where('receiver_id', $id)
                        ->first();

        if (!$existing) {
            FriendRequest::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $id,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Vriendverzoek verzonden!');
    }

    // Accept a friend request → also add to friends table
    public function acceptRequest($id)
    {
        $request = FriendRequest::findOrFail($id);

        if ($request->receiver_id != Auth::id()) {
            return back()->with('error', 'Not authorized.');
        }

        // Add friendship to friends table
        Friend::create([
            'user_id' => $request->sender_id,
            'friend_id' => $request->receiver_id,
        ]);

        // Mark request as accepted
        $request->update(['status' => 'accepted']);

        return back()->with('success', 'Vriend toegevoegd!');
    }

    // Decline a friend request → delete record
    public function declineRequest($id)
    {
        $request = FriendRequest::findOrFail($id);

        if ($request->receiver_id != Auth::id()) {
            return back()->with('error', 'Not authorized.');
        }

        $request->delete();

        return back()->with('success', 'Vriendverzoek geweigerd.');
    }

    // View pending requests
    public function pendingRequests()
    {
        $requests = FriendRequest::with('sender')
                        ->where('receiver_id', Auth::id())
                        ->where('status', 'pending')
                        ->get();

        return view('friends.requests', compact('requests'));
    }
}
