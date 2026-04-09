<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\InvitationView;
use Illuminate\Http\Request;

class PublicInvitationController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $invitation = Invitation::with([
            'template.category',
            'sections',
            'galleries',
            'guestBooks',
            'rsvps',
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        // ── Track unique view per session ─────────────
        $sessionKey = 'viewed_' . $invitation->id;
        if (!session()->has($sessionKey)) {
            InvitationView::create([
                'invitation_id' => $invitation->id,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'referrer'      => $request->headers->get('referer'),
            ]);
            session()->put($sessionKey, true);
        }

        // ── Resolve template view ─────────────────────
        $categorySlug = $invitation->template->category->slug;
        $templateView = "invitation.templates.{$categorySlug}.default";

        if (!view()->exists($templateView)) {
            $templateView = 'invitation.templates.general.default';
        }

        // ── Key sections by type for easy blade access ─
        $sections = $invitation->sections
            ->where('is_visible', true)
            ->sortBy('order')
            ->keyBy('section_type');

        // ── Guest name from URL ?to=Name ──────────────
        $guestName = $request->query('to', '');

        return view('invitation.show', compact(
            'invitation',
            'sections',
            'templateView',
            'guestName',
        ));
    }
}