<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\InvitationSection;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // Update Single Section Content
    public function update(Request $request, Invitation $invitation, InvitationSection $section)
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'content'    => 'required|array',
            'is_visible' => 'boolean',
        ]);

        $section->update([
            'content'    => $request->content,
            'is_visible' => $request->is_visible ?? $section->is_visible,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully!',
            'section' => $section->fresh(),
        ]);
    }

    // Toggle Section Visibility
    public function toggleVisibility(Invitation $invitation, InvitationSection $section)
    {
        $this->authorize('update', $invitation);

        $section->update([
            'is_visible' => !$section->is_visible,
        ]);

        return response()->json([
            'success'    => true,
            'is_visible' => $section->is_visible,
            'message'    => $section->is_visible ? 'Section shown' : 'Section hidden',
        ]);
    }

    // Reorder Sections
    public function reorder(Request $request, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'sections'   => 'required|array',
            'sections.*' => 'exists:invitation_sections,id',
        ]);

        foreach ($request->sections as $order => $sectionId) {
            $invitation->sections()
                ->where('id', $sectionId)
                ->update(['order' => $order + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sections reordered successfully!',
        ]);
    }
}