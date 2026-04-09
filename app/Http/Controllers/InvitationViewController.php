<?php
namespace App\Http\Controllers;

use App\Models\GuestBook;
use App\Models\Invitation;
use App\Models\InvitationView;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class InvitationViewController extends Controller
{
    // Show Public Invitation
    public function show(string $slug)
    {
        $invitation = Invitation::with([
            'template.category',
            'sections',
            'galleries',
            'guestBooks' => fn($q) => $q->where('is_approved', true)->latest(),
            'rsvps'
        ])->where('slug', $slug)
          ->where('status', 'published')
          ->firstOrFail();

        // Track View
        InvitationView::create([
            'invitation_id' => $invitation->id,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'referrer'      => request()->headers->get('referer'),
        ]);

        // Load the template view dynamically
        $templateView = 'templates.' . $invitation->template->slug;

        return view($templateView, compact('invitation'));
    }

    // Submit Guest Book
    public function submitGuestBook(Request $request, Invitation $invitation)
    {
        $request->validate([
            'guest_name'    => 'required|string|max:100',
            'guest_message' => 'required|string|max:500',
        ]);

        GuestBook::create([
            'invitation_id' => $invitation->id,
            'guest_name'    => $request->guest_name,
            'guest_message' => $request->guest_message,
        ]);

        return back()->with('success', 'Message sent! 💌');
    }

    // Submit RSVP
    public function submitRsvp(Request $request, Invitation $invitation)
    {
        $request->validate([
            'guest_name'   => 'required|string|max:100',
            'attendance'   => 'required|in:yes,no,maybe',
            'total_person' => 'required|integer|min:1|max:10',
            'guest_phone'  => 'nullable|string|max:20',
            'message'      => 'nullable|string|max:300',
        ]);

        Rsvp::create([
            'invitation_id' => $invitation->id,
            'guest_name'    => $request->guest_name,
            'guest_phone'   => $request->guest_phone,
            'attendance'    => $request->attendance,
            'total_person'  => $request->total_person,
            'message'       => $request->message,
        ]);

        return back()->with('success', 'RSVP submitted! Thank you 🎉');
    }
}
