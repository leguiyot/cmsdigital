<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Section;
use App\Models\FeaturedBlock;
use Illuminate\Http\Request;

/**
 * Controlador para la página principal del diario digital
 * Gestiona la presentación de contenido en la portada
 */
class HomeController extends Controller
{
    /**
     * Muestra la página principal del diario digital
     * Obtiene y organiza todos los contenidos para la portada:
     * - Artículos destacados (ordenados por featured_at)
     * - Secciones activas con sus subsecciones
     * - Últimos artículos por sección principal
     * - Artículos más leídos de la semana
     * - Últimas noticias generales
     * 
     * OPTIMIZADO: Implementa caché para reducir consultas a BD
     * 
     * @return \Illuminate\View\View - Vista de la página principal con datos
     */
    public function index()
    {
        // Obtener artículos destacados con caché (5 minutos)
        // El último artículo marcado como destacado aparece primero en la portada
        $featuredArticles = cache()->remember('home.featured_articles', 300, function () {
            return Article::where('status', 'published')
                ->where('is_featured', true)
                ->with(['author:id,name', 'section:id,name,slug'])
                ->orderBy('featured_at', 'desc') // El último marcado como destacado va primero
                ->orderBy('published_at', 'desc') // Luego por fecha de publicación
                ->take(5)
                ->get();
        });

        // Obtener secciones activas con sus subsecciones jerárquicas (30 minutos)
        // Incluye solo secciones habilitadas y ordena por configuración
        $sections = cache()->remember('home.sections', 1800, function () {
            return Section::where('is_active', true)
                ->with([
                    'children' => function ($query) {
                        $query->where('is_active', true)
                            ->orderBy('order')
                            ->orderBy('name')
                            ->select('id', 'name', 'slug', 'parent_id', 'order', 'is_active');
                    }
                ])
                ->orderBy('order')
                ->orderBy('name')
                ->get();
        });

        // Obtener últimos artículos por sección principal (10 minutos)
        // Agrupa contenido por sección para mostrar en diferentes bloques de la portada
        $latestBySection = cache()->remember('home.latest_by_section', 600, function () use ($sections) {
            $result = [];
            foreach ($sections->whereNull('parent_id')->take(6) as $section) {
                $result[$section->slug] = Article::where('status', 'published')
                    ->where('section_id', $section->id)
                    ->with(['author:id,name'])
                    ->select('id', 'title', 'slug', 'excerpt', 'published_at', 'author_id', 'section_id')
                    ->orderBy('published_at', 'desc')
                    ->take(4)
                    ->get();
            }
            return $result;
        });

        // Obtener artículos más leídos de la última semana (15 minutos)
        // Basado en views_count para mostrar contenido popular
        $mostRead = cache()->remember('home.most_read', 900, function () {
            return Article::where('status', 'published')
                ->with(['author:id,name', 'section:id,name,slug'])
                ->where('published_at', '>=', now()->subWeek())
                ->orderBy('views_count', 'desc')
                ->select('id', 'title', 'slug', 'excerpt', 'published_at', 'author_id', 'section_id', 'views_count')
                ->take(5)
                ->get();
        });

        // Obtener últimas noticias publicadas (10 minutos)
        // Lista general de artículos más recientes para sidebar o sección de noticias
        $latestNews = cache()->remember('home.latest_news', 600, function () {
            return Article::where('status', 'published')
                ->with(['author:id,name', 'section:id,name,slug'])
                ->orderBy('published_at', 'desc')
                ->select('id', 'title', 'slug', 'excerpt', 'published_at', 'author_id', 'section_id')
                ->take(10)
                ->get();
        });

        // Retornar vista con todos los datos organizados para la portada
        return view('home', compact(
            'featuredArticles',
            'sections',
            'latestBySection',
            'mostRead',
            'latestNews'
        ));
    }

}
