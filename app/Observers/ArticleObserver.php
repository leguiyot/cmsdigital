<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\ArticleCacheService;

/**
 * Observer para el modelo Article
 * Automatiza tareas cuando se crean, actualizan o eliminan artículos
 * Principalmente se encarga de invalidar caché automáticamente
 */
class ArticleObserver
{
    protected ArticleCacheService $cacheService;

    public function __construct(ArticleCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Se ejecuta después de crear un artículo
     * Invalida caché de la página principal
     * 
     * @param Article $article
     * @return void
     */
    public function created(Article $article): void
    {
        // Invalidar caché de la página principal
        $this->cacheService->invalidateHomeCache();
    }

    /**
     * Se ejecuta después de actualizar un artículo
     * Invalida caché del artículo y de la página principal
     * 
     * @param Article $article
     * @return void
     */
    public function updated(Article $article): void
    {
        // Invalidar caché del artículo específico
        $this->cacheService->invalidateArticle($article);

        // Invalidar caché de la página principal
        $this->cacheService->invalidateHomeCache();
    }

    /**
     * Se ejecuta después de eliminar un artículo
     * Invalida caché del artículo y de la página principal
     * 
     * @param Article $article
     * @return void
     */
    public function deleted(Article $article): void
    {
        // Invalidar caché del artículo específico
        $this->cacheService->invalidateArticle($article);

        // Invalidar caché de la página principal
        $this->cacheService->invalidateHomeCache();
    }

    /**
     * Se ejecuta después de guardar un artículo (crear o actualizar)
     * Útil para tareas que deben ejecutarse en ambos casos
     * 
     * @param Article $article
     * @return void
     */
    public function saved(Article $article): void
    {
        // Si el artículo cambió de estado a publicado, invalidar caché
        if ($article->wasChanged('status') && $article->status === 'published') {
            $this->cacheService->invalidateHomeCache();
        }

        // Si cambió el estado de destacado, invalidar caché
        if ($article->wasChanged('is_featured')) {
            $this->cacheService->invalidateHomeCache();
        }
    }
}
