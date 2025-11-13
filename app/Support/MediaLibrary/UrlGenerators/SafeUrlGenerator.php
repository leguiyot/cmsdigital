<?php

namespace App\Support\MediaLibrary\UrlGenerators;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

/**
 * UrlGenerator personalizado que maneja mejor los casos donde disk es null
 */
class SafeUrlGenerator extends DefaultUrlGenerator
{
    /**
     * Override del método que está causando problemas
     */
    public function getDiskName(): string
    {
        $disk = $this->media->disk;
        
        // Si disk es null o vacío, usar el disco por defecto
        if (empty($disk)) {
            $disk = config('media-library.disk_name', 'public');
        }
        
        // Si todavía es null, usar public como fallback final
        if (empty($disk)) {
            $disk = 'public';
        }
        
        return $disk;
    }
    
    /**
     * Generar URL de forma segura
     */
    public function getUrl(): string
    {
        try {
            return parent::getUrl();
        } catch (\Exception $e) {
            // Fallback manual si todo falla
            $date = $this->media->created_at ?? now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $collection = $this->media->collection_name ?: 'default';
            
            $host = request() ? request()->getSchemeAndHttpHost() : config('app.url');
            return $host . "/storage/articles/{$collection}/{$year}/{$month}/{$this->media->file_name}";
        }
    }
}
