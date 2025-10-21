<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Section;
use App\Models\FeaturedBlock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página principal del diario digital
     */
    public function index()
    {
        // Obtener artículos destacados
        $featuredArticles = Article::where('status', 'published')
            ->where('is_featured', true)
            ->with(['author', 'section'])
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Obtener secciones activas
        $sections = Section::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Obtener últimos artículos por sección
        $latestBySection = [];
        foreach ($sections->take(6) as $section) {
            $latestBySection[$section->slug] = Article::where('status', 'published')
                ->where('section_id', $section->id)
                ->with(['author'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }

        // Obtener artículos más leídos de la semana
        $mostRead = Article::where('status', 'published')
            ->with(['author', 'section'])
            ->where('published_at', '>=', now()->subWeek())
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Obtener últimas noticias
        $latestNews = Article::where('status', 'published')
            ->with(['author', 'section'])
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return view('home', compact(
            'featuredArticles',
            'sections',
            'latestBySection',
            'mostRead',
            'latestNews'
        ));
    }
}
