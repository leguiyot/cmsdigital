<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Modelo para elementos de la galería de medios
 * Utilizado como contenedor para archivos independientes de artículos
 */
class MediaItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'is_gallery_item',
    ];

    protected function casts(): array
    {
        return [
            'is_gallery_item' => 'boolean',
        ];
    }

    /**
     * Registra las colecciones de medios disponibles
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk('uploads')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

        $this->addMediaCollection('cover')
            ->useDisk('uploads')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('documents')
            ->useDisk('uploads')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * Registra las conversiones de medios
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600)
            ->optimize()
            ->nonQueued();
    }
}
