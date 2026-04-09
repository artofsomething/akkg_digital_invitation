<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invitation;

class DashboardController extends Controller
{
    public function index()
    {
        $invitations = auth()->user()
            ->invitations()
            ->with(['template.category', 'views', 'rsvps', 'guestBooks'])
            ->latest()
            ->get();

        $stats = [
            'total_invitations' => $invitations->count(),
            'total_published'   => $invitations->where('status', 'published')->count(),
            'total_views'       => $invitations->sum(fn($i) => $i->views->count()),
            'total_rsvps'       => $invitations->sum(fn($i) => $i->rsvps->count()),
        ];

        return view('user.dashboard', compact('invitations', 'stats'));
    }

    public function analytics(Invitation $invitation)
    {
        $this->authorize('view', $invitation);

        // Views per day (last 30 days)
        $viewsPerDay = $invitation->views()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // RSVP Stats
        $rsvpStats = [
            'yes'   => $invitation->rsvps()->where('attendance', 'yes')->count(),
            'no'    => $invitation->rsvps()->where('attendance', 'no')->count(),
            'maybe' => $invitation->rsvps()->where('attendance', 'maybe')->count(),
        ];

        $totalViews     = $invitation->views()->count();
        $totalGuests    = $invitation->rsvps()->sum('total_person');
        $totalGuestBook = $invitation->guestBooks()->count();
        $recentRsvps    = $invitation->rsvps()->latest()->take(10)->get();
        $recentMessages = $invitation->guestBooks()->latest()->take(10)->get();

        return view('user.analytics', compact(
            'invitation', 'viewsPerDay', 'rsvpStats',
            'totalViews', 'totalGuests', 'totalGuestBook',
            'recentRsvps', 'recentMessages'
        ));
    }
}