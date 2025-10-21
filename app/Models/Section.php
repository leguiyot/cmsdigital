<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Section extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'order',
        'color',
        'icon',
        'is_active',
        'show_in_menu',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_in_menu' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Configuración del slug
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Relación con sección padre
     */
    public function parent()
    {
        return $this->belongsTo(Section::class, 'parent_id');
    }

    /**
     * Relación con secciones hijas
     */
    public function children()
    {
        return $this->hasMany(Section::class, 'parent_id')
                    ->orderBy('order');
    }

    /**
     * Relación con artículos
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Artículos publicados de la sección
     */
    public function publishedArticles()
    {
        return $this->hasMany(Article::class)
                    ->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope para secciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para secciones en el menú
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    /**
     * Scope para secciones principales (sin padre)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Obtener la URL de la sección
     */
    public function getUrlAttribute(): string
    {
        return route('sections.show', $this->slug);
    }
}
