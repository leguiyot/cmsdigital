<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Section::withCount('articles');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'articles_count':
                $query->orderBy('articles_count', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $sections = $query->paginate(10)->appends($request->query());

        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sections.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sections',
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $sectionData = $request->only([
            'name', 'slug', 'description', 'seo_title', 'meta_description',
            'is_active', 'is_featured', 'order'
        ]);

        // Generate slug if not provided
        if (empty($sectionData['slug'])) {
            $sectionData['slug'] = Str::slug($sectionData['name']);
        } else {
            $sectionData['slug'] = Str::slug($sectionData['slug']);
        }

        // Ensure slug is unique
        $originalSlug = $sectionData['slug'];
        $counter = 1;
        while (Section::where('slug', $sectionData['slug'])->exists()) {
            $sectionData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Process meta_keywords as array
        if ($request->meta_keywords) {
            $sectionData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        }

        // Set defaults
        $sectionData['is_active'] = $request->boolean('is_active', true);
        $sectionData['is_featured'] = $request->boolean('is_featured', false);
        $sectionData['order'] = $request->get('order', 0);

        Section::create($sectionData);

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $section = Section::where('slug', $slug)->firstOrFail();
        
        // This would be for the public view
        $articles = $section->articles()
                           ->where('status', 'published')
                           ->orderBy('published_at', 'desc')
                           ->paginate(10);

        return view('sections.show', compact('section', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('admin.sections.form', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('sections')->ignore($section->id)],
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $sectionData = $request->only([
            'name', 'slug', 'description', 'seo_title', 'meta_description',
            'is_active', 'is_featured', 'order'
        ]);

        // Generate slug if not provided
        if (empty($sectionData['slug'])) {
            $sectionData['slug'] = Str::slug($sectionData['name']);
        } else {
            $sectionData['slug'] = Str::slug($sectionData['slug']);
        }

        // Ensure slug is unique (excluding current section)
        $originalSlug = $sectionData['slug'];
        $counter = 1;
        while (Section::where('slug', $sectionData['slug'])->where('id', '!=', $section->id)->exists()) {
            $sectionData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Process meta_keywords as array
        if ($request->meta_keywords) {
            $sectionData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        } else {
            $sectionData['meta_keywords'] = null;
        }

        // Set defaults
        $sectionData['is_active'] = $request->boolean('is_active');
        $sectionData['is_featured'] = $request->boolean('is_featured');
        $sectionData['order'] = $request->get('order', 0);

        $section->update($sectionData);

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        // Check if section has articles
        if ($section->articles()->count() > 0) {
            return redirect()->route('admin.sections.index')
                            ->with('error', 'No se puede eliminar la sección porque tiene artículos asociados.');
        }

        $section->delete();

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección eliminada exitosamente.');
    }
}
