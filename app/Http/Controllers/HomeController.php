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
     * @return \Illuminate\View\View - Vista de la página principal con datos
     */
    public function index()
    {
        // Obtener artículos destacados (ordenados por última vez marcados como destacados)
        // El último artículo marcado como destacado aparece primero en la portada
        $featuredArticles = Article::where('status', 'published')
            ->where('is_featured', true)
            ->with(['author', 'section'])
            ->orderBy('featured_at', 'desc') // El último marcado como destacado va primero
            ->orderBy('published_at', 'desc') // Luego por fecha de publicación
            ->take(5)
            ->get();

        // Obtener secciones activas con sus subsecciones jerárquicas
        // Incluye solo secciones habilitadas y ordena por configuración
        $sections = Section::where('is_active', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)->orderBy('order')->orderBy('name');
            }])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Obtener últimos artículos por sección principal (sin parent_id)
        // Agrupa contenido por sección para mostrar en diferentes bloques de la portada
        $latestBySection = [];
        foreach ($sections->whereNull('parent_id')->take(6) as $section) {
            $latestBySection[$section->slug] = Article::where('status', 'published')
                ->where('section_id', $section->id)
                ->with(['author'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }

        // Obtener artículos más leídos de la última semana
        // Basado en views_count para mostrar contenido popular
        $mostRead = Article::where('status', 'published')
            ->with(['author', 'section'])
            ->where('published_at', '>=', now()->subWeek())
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Obtener últimas noticias publicadas
        // Lista general de artículos más recientes para sidebar o sección de noticias
        $latestNews = Article::where('status', 'published')
            ->with(['author', 'section'])
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

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
