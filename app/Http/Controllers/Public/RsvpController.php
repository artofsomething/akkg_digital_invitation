<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $invitation = Invitation::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'guest_name'   => 'required|string|max:100',
            'guest_phone'  => 'nullable|string|max:20',
            'attendance'   => 'required|in:yes,no,maybe',
            'total_person' => 'required|integer|min:1|max:20',
            'message'      => 'nullable|string|max:500',
        ]);

        // Update if already RSVP'd
        $rsvp = Rsvp::updateOrCreate(
            [
                'invitation_id' => $invitation->id,
                'guest_name'    => $validated['guest_name'],
            ],
            array_merge($validated, ['invitation_id' => $invitation->id])
        );

        $responses = [
            'yes'   => ['message' => "See you there! 🎉", 'color' => 'green'],
            'no'    => ['message' => "Sorry to miss you 💐", 'color' => 'red'],
            'maybe' => ['message' => "We'll keep a spot! 🤞", 'color' => 'yellow'],
        ];

        return response()->json([
            'success'    => true,
            'message'    => $responses[$rsvp->attendance]['message'],
            'color'      => $responses[$rsvp->attendance]['color'],
            'attendance' => $rsvp->attendance,
            'data'       => [
                'guest_name'   => $rsvp->guest_name,
                'total_person' => $rsvp->total_person,
            ],
        ]);
    }
}