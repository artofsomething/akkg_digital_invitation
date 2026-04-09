<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function store(Request $request, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'images.*'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $uploaded = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('galleries/' . $invitation->id, 'public');

            $gallery = Gallery::create([
                'invitation_id' => $invitation->id,
                'image_path'    => $path,
                'caption'       => '',
                'order'         => $invitation->galleries()->count() + 1,
            ]);

            $uploaded[] = [
                'id'        => $gallery->id,
                'image_url' => asset('storage/' . $path),
                'caption'   => '',
                'order'     => $gallery->order,
            ];
        }

        return response()->json([
            'success'  => true,
            'message'  => count($uploaded) . ' image(s) uploaded successfully!',
            'images'   => $uploaded,
        ]);
    }

    // Update Caption
    public function updateCaption(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery->invitation);

        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $gallery->update(['caption' => $request->caption]);

        return response()->json([
            'success' => true,
            'message' => 'Caption updated!',
        ]);
    }

    // Delete Image
    public function destroy(Gallery $gallery)
    {
        $this->authorize('update', $gallery->invitation);

        // Delete file from storage
        Storage::disk('public')->delete($gallery->image_path);

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!',
        ]);
    }

    // Reorder Images
    public function reorder(Request $request, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'exists:galleries,id',
        ]);

        foreach ($request->images as $order => $imageId) {
            Gallery::where('id', $imageId)
                ->where('invitation_id', $invitation->id)
                ->update(['order' => $order + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Images reordered!',
        ]);
    }
}
