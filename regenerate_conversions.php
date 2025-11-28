<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║  REGENERACIÓN MANUAL DE CONVERSIONES DE IMÁGENES          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Obtener todos los medios
$allMedia = Media::where('disk', 'uploads')
    ->where('mime_type', 'like', 'image/%')
    ->get();

echo "📊 Total de imágenes encontradas: {$allMedia->count()}\n\n";

$success = 0;
$errors = 0;

foreach ($allMedia as $media) {
    echo "📸 Procesando: {$media->file_name}\n";
    echo "   Colección: {$media->collection_name}\n";
    
    try {
        // Verificar que el archivo original existe
        if (!file_exists($media->getPath())) {
            echo "   ❌ Archivo original no encontrado: {$media->getPath()}\n\n";
            $errors++;
            continue;
        }
        
        // Obtener el path para conversiones
        $date = $media->created_at ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $collection = $media->collection_name ?: 'default';
        
        $conversionsDir = public_path("uploads/articles/{$collection}/{$year}/{$month}/conversions");
        
        // Crear directorio de conversiones si no existe
        if (!File::isDirectory($conversionsDir)) {
            File::makeDirectory($conversionsDir, 0755, true);
            echo "   📁 Creado directorio: {$conversionsDir}\n";
        }
        
        // Generar conversiones
        $media->manipulations = [];
        $media->generated_conversions = [];
        $media->save();
        
        // Regenerar conversiones
        $conversions = ['thumb', 'medium'];
        foreach ($conversions as $conversion) {
            try {
                $media->getUrl($conversion);
                echo "   ✅ Conversión '{$conversion}' generada\n";
            } catch (\Exception $e) {
                echo "   ⚠️  Error al generar conversión '{$conversion}': {$e->getMessage()}\n";
            }
        }
        
        $success++;
        echo "   ✅ Completado\n\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Error: {$e->getMessage()}\n\n";
        $errors++;
    }
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "📊 RESUMEN:\n";
echo "   ✅ Procesadas exitosamente: {$success}\n";
echo "   ❌ Errores: {$errors}\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
