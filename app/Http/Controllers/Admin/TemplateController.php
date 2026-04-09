<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('category')
            ->withCount('invitations')
            ->latest()
            ->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.templates.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255|unique:templates,name',
            'thumbnail'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'font_family'  => 'nullable|string|max:100',
            'is_premium'   => 'boolean',
            'is_active'    => 'boolean',
            'color_primary'   => 'nullable|string|max:7',
            'color_secondary' => 'nullable|string|max:7',
            'color_accent'    => 'nullable|string|max:7',
            'color_text'      => 'nullable|string|max:7',
        ]);

        // Upload thumbnail
        $thumbnailPath = $request->file('thumbnail')
            ->store('templates/thumbnails', 'public');

        Template::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'thumbnail'    => $thumbnailPath,
            'font_family'  => $request->font_family,
            'is_premium'   => $request->boolean('is_premium'),
            'is_active'    => $request->boolean('is_active', true),
            'color_scheme' => [
                'primary'   => $request->color_primary   ?? '#000000',
                'secondary' => $request->color_secondary ?? '#ffffff',
                'accent'    => $request->color_accent    ?? '#cccccc',
                'text'      => $request->color_text      ?? '#333333',
            ],
        ]);

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template created successfully!');
    }

    public function edit(Template $template)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:255|unique:templates,name,' . $template->id,
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'font_family'  => 'nullable|string|max:100',
            'is_premium'   => 'boolean',
            'is_active'    => 'boolean',
            'color_primary'   => 'nullable|string|max:7',
            'color_secondary' => 'nullable|string|max:7',
            'color_accent'    => 'nullable|string|max:7',
            'color_text'      => 'nullable|string|max:7',
        ]);

        $data = [
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'font_family'  => $request->font_family,
            'is_premium'   => $request->boolean('is_premium'),
            'is_active'    => $request->boolean('is_active'),
            'color_scheme' => [
                'primary'   => $request->color_primary   ?? '#000000',
                'secondary' => $request->color_secondary ?? '#ffffff',
                'accent'    => $request->color_accent    ?? '#cccccc',
                'text'      => $request->color_text      ?? '#333333',
            ],
        ];

        // Update thumbnail if new file uploaded
        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->delete($template->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('templates/thumbnails', 'public');
        }

        $template->update($data);

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template updated successfully!');
    }

    public function destroy(Template $template)
    {
        Storage::disk('public')->delete($template->thumbnail);
        $template->delete();

        return back()->with('success', 'Template deleted!');
    }
}