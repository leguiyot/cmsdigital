<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Modelo Media personalizado que asegura que siempre tenga disco definido
 */
class CustomMedia extends Media
{
    /**
     * Boot del modelo para asegurar que siempre tenga disco
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($media) {
            if (empty($media->disk)) {
                $media->disk = config('media-library.disk_name', 'uploads');
            }
        });
        
        static::updating(function ($media) {
            if (empty($media->disk)) {
                $media->disk = config('media-library.disk_name', 'uploads');
            }
        });
    }

    /**
     * Override del método getDisk para asegurar que nunca sea null
     */
    public function getDisk(): string
    {
        $disk = $this->disk ?: config('media-library.disk_name', 'uploads');
        
        if (empty($disk)) {
            // Fallback si todo falla
            $disk = 'uploads';
        }
        
        return $disk;
    }

    /**
     * Override del método getUrl para manejar errores
     */
    public function getUrl(string $conversionName = ''): string
    {
        try {
            return parent::getUrl($conversionName);
        } catch (\Exception $e) {
            // Construir URL manualmente como fallback usando el host actual
            $date = $this->created_at ?? now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $collection = $this->collection_name ?: 'default';
            
            // Usar el host actual en lugar de APP_URL fijo
            $host = request() ? request()->getSchemeAndHttpHost() : config('app.url');
            
            $filename = $this->file_name;
            if (!empty($conversionName)) {
                $filename = pathinfo($this->file_name, PATHINFO_FILENAME) . '-' . $conversionName . '.' . pathinfo($this->file_name, PATHINFO_EXTENSION);
                return $host . "/uploads/articles/{$collection}/{$year}/{$month}/conversions/{$conversionName}/{$filename}";
            }
            
            return $host . "/uploads/articles/{$collection}/{$year}/{$month}/{$filename}";
        }
    }
}
