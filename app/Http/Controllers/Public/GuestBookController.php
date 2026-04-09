<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Invitation;
use Illuminate\Http\Request;

class GuestBookController extends Controller
{
    public function index(string $slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $guestBooks = $invitation->guestBooks()
            ->latest()
            ->take(50)
            ->get()
            ->map(fn($gb) => [
                'id'            => $gb->id,
                'guest_name'    => $gb->guest_name,
                'guest_message' => $gb->guest_message,
                'time_ago'      => $gb->created_at->diffForHumans(),
            ]);

        return response()->json([
            'success'     => true,
            'guest_books' => $guestBooks,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'guest_name'    => 'required|string|max:100',
            'guest_message' => 'required|string|max:500',
        ]);

        $guestBook = GuestBook::create([
            'invitation_id' => $invitation->id,
            'guest_name'    => $validated['guest_name'],
            'guest_message' => $validated['guest_message'],
            'is_approved'   => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent! 💌',
            'data'    => [
                'id'            => $guestBook->id,
                'guest_name'    => $guestBook->guest_name,
                'guest_message' => $guestBook->guest_message,
                'time_ago'      => 'Just now',
            ],
        ]);
    }
}