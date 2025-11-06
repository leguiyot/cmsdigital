<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class FixMediaDisk
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Antes de procesar la request, asegurar que todos los medios tengan disco
        $this->ensureMediaHaveDisk();
        
        return $next($request);
    }
    
    /**
     * Asegurar que todos los medios en la base de datos tengan disco especificado
     */
    private function ensureMediaHaveDisk(): void
    {
        try {
            // Buscar medios sin disco y actualizarlos
            $count = Media::whereNull('disk')->orWhere('disk', '')->update(['disk' => 'uploads']);
            
            if ($count > 0) {
                Log::info("FixMediaDisk middleware fixed {$count} media records without disk");
            }
        } catch (\Exception $e) {
            Log::error("FixMediaDisk middleware error: " . $e->getMessage());
        }
    }
}
