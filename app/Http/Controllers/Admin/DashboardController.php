<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\Template;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Simplest possible response to test
        return view('admin.dashboard', [
            'stats' => [
                'total_users'       => 0,
                'total_invitations' => 0,
                'total_published'   => 0,
                'total_templates'   => 0,
            ],
            'popularTemplates'  => collect([]),
            'recentUsers'       => collect([]),
            'recentInvitations' => collect([]),
        ]);
        $stats = [
            'total_users'       => User::role('user')->count(),
            'total_invitations' => Invitation::count(),
            'total_published'   => Invitation::where('status', 'published')->count(),
            'total_templates'   => Template::count(),
        ];

        $popularTemplates = Template::withCount('invitations')
            ->orderByDesc('invitations_count')
            ->take(5)
            ->get();

        $recentUsers = User::withCount('invitations')
            ->latest()
            ->take(10)
            ->get();

        $recentInvitations = Invitation::with(['user', 'template'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'popularTemplates',
            'recentUsers',
            'recentInvitations'
        ));
    }

    public function users()
    {
        $users = User::withCount('invitations')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function userDetail(User $user)
    {
        $invitations = $user->invitations()
            ->with(['template', 'views', 'rsvps', 'guestBooks'])
            ->latest()
            ->get();

        $stats = [
            'total_invitations' => $invitations->count(),
            'total_views'       => $invitations->sum(fn($i) => $i->views->count()),
            'total_rsvps'       => $invitations->sum(fn($i) => $i->rsvps->count()),
        ];

        return view('admin.users.detail', compact('user', 'invitations', 'stats'));
    }
}