<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;

/**
 * Servicio centralizado para gestión de caché de artículos
 * Proporciona métodos para cachear, invalidar y precalentar datos de artículos
 */
class ArticleCacheService
{
    /**
     * Tiempo de caché por defecto (60 minutos)
     */
    const DEFAULT_TTL = 3600;

    /**
     * Obtiene un artículo desde caché o lo carga si no existe
     * 
     * @param string $slug - Slug del artículo
     * @param int $ttl - Tiempo de vida del caché en segundos
     * @return Article|null
     */
    public function getCachedArticle(string $slug, int $ttl = self::DEFAULT_TTL): ?Article
    {
        return Cache::remember("article.{$slug}", $ttl, function () use ($slug) {
            return Article::where('slug', $slug)
                ->published()
                ->with([
                    'author:id,name,email,bio',
                    'section:id,name,slug',
                    'media'
                ])
                ->first();
        });
    }

    /**
     * Invalida el caché de un artículo específico
     * 
     * @param Article $article - Artículo a invalidar
     * @return void
     */
    public function invalidateArticle(Article $article): void
    {
        Cache::forget("article.{$article->slug}");
    }

    /**
     * Invalida todo el caché relacionado con artículos
     * Útil cuando se hacen cambios masivos
     * 
     * @return void
     */
    public function invalidateAll(): void
    {
        // Invalidar caché de la página principal
        Cache::forget('home.featured_articles');
        Cache::forget('home.latest_by_section');
        Cache::forget('home.most_read');
        Cache::forget('home.latest_news');

        // Invalidar caché de secciones (afecta a artículos)
        Cache::forget('home.sections');
        Cache::forget('admin.sections_filter');
    }

    /**
     * Invalida caché de la página principal
     * Se llama cuando se crea, actualiza o elimina un artículo
     * 
     * @return void
     */
    public function invalidateHomeCache(): void
    {
        Cache::forget('home.featured_articles');
        Cache::forget('home.latest_by_section');
        Cache::forget('home.most_read');
        Cache::forget('home.latest_news');
    }

    /**
     * Precalienta el caché de artículos más visitados
     * Útil para ejecutar en un cron job
     * 
     * @param int $limit - Número de artículos a precalentar
     * @return int - Número de artículos precalentados
     */
    public function warmupCache(int $limit = 50): int
    {
        $articles = Article::published()
            ->orderBy('views_count', 'desc')
            ->take($limit)
            ->get(['slug']);

        $count = 0;
        foreach ($articles as $article) {
            $this->getCachedArticle($article->slug);
            $count++;
        }

        return $count;
    }

    /**
     * Obtiene estadísticas del caché
     * 
     * @return array
     */
    public function getCacheStats(): array
    {
        return [
            'home_featured' => Cache::has('home.featured_articles'),
            'home_sections' => Cache::has('home.sections'),
            'home_latest_by_section' => Cache::has('home.latest_by_section'),
            'home_most_read' => Cache::has('home.most_read'),
            'home_latest_news' => Cache::has('home.latest_news'),
        ];
    }
}
