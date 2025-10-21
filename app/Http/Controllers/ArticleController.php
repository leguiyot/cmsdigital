<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with(['author', 'section']);

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhere('body', 'like', "%{$searchTerm}%")
                  ->orWhereHas('author', function ($authorQuery) use ($searchTerm) {
                      $authorQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por sección
        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        // Ordenar por fecha de creación descendente
        $query->orderBy('created_at', 'desc');

        $articles = $query->paginate(15)->withQueryString();
        $sections = Section::active()->orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('admin.articles.form', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:draft,review,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        $articleData = $request->only([
            'title', 'excerpt', 'body', 'section_id', 'status',
            'seo_title', 'meta_description', 'is_featured', 'allow_comments'
        ]);

        // Handle published_at
        if ($request->status === 'published' && !$request->published_at) {
            $articleData['published_at'] = now();
        } elseif ($request->published_at) {
            $articleData['published_at'] = $request->published_at;
        }

        // Process meta_keywords and tags as arrays
        if ($request->meta_keywords) {
            $articleData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        }

        if ($request->tags) {
            $articleData['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Set author
        $articleData['author_id'] = Auth::id();

        // Calculate reading time (average reading speed: 200 words per minute)
        $wordCount = str_word_count(strip_tags($request->body));
        $articleData['reading_time'] = max(1, ceil($wordCount / 200));

        $article = Article::create($articleData);

        return redirect()->route('admin.articles.index')
                        ->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
                          ->published()
                          ->with(['author', 'section', 'comments' => function ($query) {
                              $query->approved()->main()->with(['user', 'replies']);
                          }])
                          ->firstOrFail();

        // Incrementar contador de vistas
        $article->incrementViews();

        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $sections = Section::all();
        return view('admin.articles.form', compact('article', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:draft,review,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        $articleData = $request->only([
            'title', 'excerpt', 'body', 'section_id', 'status',
            'seo_title', 'meta_description', 'is_featured', 'allow_comments'
        ]);

        // Handle published_at
        if ($request->status === 'published' && !$article->published_at && !$request->published_at) {
            $articleData['published_at'] = now();
        } elseif ($request->published_at) {
            $articleData['published_at'] = $request->published_at;
        } elseif ($request->status !== 'published') {
            $articleData['published_at'] = null;
        }

        // Process meta_keywords and tags as arrays
        if ($request->meta_keywords) {
            $articleData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        } else {
            $articleData['meta_keywords'] = null;
        }

        if ($request->tags) {
            $articleData['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $articleData['tags'] = null;
        }

        // Calculate reading time (average reading speed: 200 words per minute)
        $wordCount = str_word_count(strip_tags($request->body));
        $articleData['reading_time'] = max(1, ceil($wordCount / 200));

        $article->update($articleData);

        return redirect()->route('admin.articles.index')
                        ->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
                        ->with('success', 'Artículo eliminado exitosamente.');
    }

    /**
     * Publish an article
     */
    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => $article->published_at ?? now()
        ]);

        return back()->with('success', 'Artículo publicado exitosamente.');
    }

    /**
     * Unpublish an article
     */
    public function unpublish(Article $article)
    {
        $article->update(['status' => 'draft']);

        return back()->with('success', 'Artículo despublicado exitosamente.');
    }

    /**
     * Toggle featured status
     */
    public function feature(Article $article)
    {
        $article->update(['is_featured' => !$article->is_featured]);

        $message = $article->is_featured ? 'Artículo marcado como destacado.' : 'Artículo removido de destacados.';

        return back()->with('success', $message);
    }
}
