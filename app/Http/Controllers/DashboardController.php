<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard administrativo
     */
    public function index()
    {
        $user = Auth::user();
        
        // Estadísticas generales
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::where('status', 'draft')->count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_sections' => Section::count(),
        ];

        // Artículos recientes del usuario o todos si es admin/editor
        if ($user->hasRole(['admin', 'editor'])) {
            $recentArticles = Article::with(['author', 'section'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else {
            $recentArticles = Article::where('author_id', $user->id)
                ->with(['section'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        }

        // Comentarios pendientes de moderación (solo para admin y editores)
        $pendingComments = collect();
        if ($user->hasRole(['admin', 'editor'])) {
            $pendingComments = Comment::where('status', 'pending')
                ->with(['article', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        // Artículos más leídos de la semana
        $topArticles = Article::published()
            ->with(['author', 'section'])
            ->where('published_at', '>=', now()->subWeek())
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Estadísticas por sección
        $sectionStats = Section::withCount(['articles as published_count' => function ($query) {
            $query->where('status', 'published')
                  ->where('published_at', '<=', now());
        }])
        ->orderBy('published_count', 'desc')
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentArticles',
            'pendingComments',
            'topArticles',
            'sectionStats'
        ));
    }
}
