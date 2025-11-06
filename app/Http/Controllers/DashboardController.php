<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador del panel administrativo (Dashboard)
 * Gestiona la pantalla principal del área de administración
 * Proporciona estadísticas y resúmenes para administradores y editores
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del área administrativa
     * Recopila estadísticas generales, artículos recientes, comentarios pendientes
     * y métricas importantes según el rol del usuario autenticado
     * 
     * @return \Illuminate\View\View - Vista del dashboard con estadísticas y datos
     */
    public function index()
    {
        // Obtener usuario autenticado para personalizar contenido según rol
        $user = Auth::user();
        
        // Estadísticas generales del sistema
        // Contadores básicos para mostrar resumen del estado del CMS
        $stats = [
            'total_articles' => Article::count(),                          // Total de artículos
            'published_articles' => Article::published()->count(),         // Artículos publicados
            'draft_articles' => Article::where('status', 'draft')->count(), // Borradores
            'pending_comments' => Comment::where('status', 'pending')->count(), // Comentarios pendientes
            'total_users' => User::count(),                                // Total de usuarios
            'total_sections' => Section::count(),                          // Total de secciones
        ];

        // Artículos recientes según el rol del usuario
        // Administradores/editores ven todos, autores solo los suyos
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
        // Lista de comentarios que requieren aprobación
        $pendingComments = collect();
        if ($user->hasRole(['admin', 'editor'])) {
            $pendingComments = Comment::where('status', 'pending')
                ->with(['article', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        // Artículos más leídos de la última semana
        // Métricas de rendimiento para identificar contenido popular
        $topArticles = Article::published()
            ->with(['author', 'section'])
            ->where('published_at', '>=', now()->subWeek())
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Estadísticas por sección
        // Distribución de contenido publicado por cada sección
        $sectionStats = Section::withCount(['articles as published_count' => function ($query) {
            $query->where('status', 'published')
                  ->where('published_at', '<=', now());
        }])
        ->orderBy('published_count', 'desc')
        ->take(5)
        ->get();

        // Retornar vista del dashboard con todos los datos compilados
        return view('admin.dashboard', compact(
            'stats',
            'recentArticles',
            'pendingComments',
            'topArticles',
            'sectionStats'
        ));
    }
}
