<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Generador de rutas personalizado para organizar imágenes
 * Similar al sistema de WordPress con carpetas por año/mes
 */
class CustomPathGenerator implements PathGenerator
{
    /**
     * Genera la ruta donde se almacenará el archivo original
     * Organiza por colección, año y mes
     */
    public function getPath(Media $media): string
    {
        $date = $media->created_at ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $collection = $media->collection_name ?: 'default';
        
        return "articles/{$collection}/{$year}/{$month}/";
    }

    /**
     * Genera la ruta donde se almacenarán las conversiones
     * Mantiene la misma estructura pero en subcarpeta conversions
     */
    public function getPathForConversions(Media $media): string
    {
        $date = $media->created_at ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $collection = $media->collection_name ?: 'default';
        
        return "articles/{$collection}/{$year}/{$month}/conversions/";
    }

    /**
     * Genera la ruta donde se almacenarán las imágenes responsivas
     * Mantiene la misma estructura pero en subcarpeta responsive
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        $date = $media->created_at ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $collection = $media->collection_name ?: 'default';
        
        return "articles/{$collection}/{$year}/{$month}/responsive/";
    }
}
