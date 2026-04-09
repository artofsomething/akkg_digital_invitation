<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Requests\UpdateInvitationRequest;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    // Step 1 : Select Category
    public function selectCategory()
    {
        $categories = Category::where('is_active', true)
            ->withCount('templates')
            ->get();

        return view('user.invitation.select-category', compact('categories'));
    }

    // Step 2 : Select Template
    public function selectTemplate(Category $category)
    {
        $templates = Template::where('category_id', $category->id)
            ->where('is_active', true)
            ->get();

        return view('user.invitation.select-template', compact('templates', 'category'));
    }

    // Step 3 : Fill Basic Info
    public function create(Template $template)
    {
        return view('user.invitation.create', compact('template'));
    }

    // Store Invitation
    public function store(StoreInvitationRequest $request)
    {
        $invitation = Invitation::create([
            'user_id'        => auth()->id(),
            'template_id'    => $request->template_id,
            'title'          => $request->title,
            'event_date'     => $request->event_date,
            'event_time'     => $request->event_time,
            'event_location' => $request->event_location,
            'event_address'  => $request->event_address,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'status'         => 'draft',
        ]);

        // Create Default Sections
        $this->createDefaultSections($invitation);

        // Increment template used count
        $invitation->template->increment('used_count');

        return redirect()
            ->route('user.invitation.edit', $invitation->slug)
            ->with('success', 'Invitation created! Now customize your content.');
    }

    // Edit Invitation
    public function edit(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $sections = $invitation->sections()->orderBy('order')->get();

        return view('user.invitation.edit', compact('invitation', 'sections'));
    }

    // Update Invitation
    public function update(UpdateInvitationRequest $request, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $invitation->update($request->validated());

        return back()->with('success', 'Invitation updated successfully!');
    }

    // Publish Invitation
    public function publish(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $invitation->update(['status' => 'published']);

        return back()->with('success', 'Invitation is now live! 🎉');
    }

    // Unpublish Invitation
    public function unpublish(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $invitation->update(['status' => 'draft']);

        return back()->with('success', 'Invitation set back to draft.');
    }

    // Delete Invitation
    public function destroy(Invitation $invitation)
    {
        $this->authorize('delete', $invitation);

        $invitation->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Invitation deleted successfully.');
    }

    // Create Default Sections
    private function createDefaultSections(Invitation $invitation): void
    {
        $sections = [
            [
                'section_type' => 'opening',
                'order'        => 1,
                'content'      => [
                    'title'            => $invitation->title,
                    'subtitle'         => 'We invite you to celebrate with us',
                    'background_image' => '',
                    'music_url'        => '',
                ],
            ],
            [
                'section_type' => 'profile',
                'order'        => 2,
                'content'      => [
                    'persons' => [
                        [
                            'name'   => '',
                            'role'   => '',
                            'parent' => '',
                            'photo'  => '',
                            'instagram' => '',
                        ],
                        [
                            'name'   => '',
                            'role'   => '',
                            'parent' => '',
                            'photo'  => '',
                            'instagram' => '',
                        ],
                    ],
                ],
            ],
            [
                'section_type' => 'the_date',
                'order'        => 3,
                'content'      => [
                    'title'          => 'Save The Date',
                    'date'           => $invitation->event_date,
                    'time'           => $invitation->event_time,
                    'venue'          => $invitation->event_location,
                    'show_countdown' => true,
                ],
            ],
            [
                'section_type' => 'gallery',
                'order'        => 4,
                'content'      => [
                    'title'    => 'Our Gallery',
                    'subtitle' => 'Our precious moments together',
                ],
            ],
            [
                'section_type' => 'map',
                'order'        => 5,
                'content'      => [
                    'title'   => 'Location',
                    'address' => $invitation->event_address ?? '',
                ],
            ],
            [
                'section_type' => 'guest_book',
                'order'        => 6,
                'content'      => [
                    'title'    => 'Guest Book',
                    'subtitle' => 'Leave your wishes here',
                ],
            ],
            [
                'section_type' => 'rsvp',
                'order'        => 7,
                'content'      => [
                    'title'    => 'RSVP',
                    'subtitle' => 'Please confirm your attendance',
                    'deadline' => '',
                ],
            ],
        ];

        foreach ($sections as $section) {
            $invitation->sections()->create($section);
        }
    }
}