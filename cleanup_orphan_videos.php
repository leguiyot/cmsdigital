<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Article;

echo "=== LIMPIEZA DE VIDEOS HUÉRFANOS ===\n\n";

// Obtener todos los videos
$videos = Media::where('collection_name', 'videos')->get();

echo "Videos encontrados en base de datos: " . $videos->count() . "\n\n";

$cleaned = 0;

foreach ($videos as $video) {
    echo "Verificando video: {$video->name}.{$video->extension} (ID: {$video->id})\n";
    
    // Verificar si el archivo físico existe
    $fullPath = $video->getPath();
    echo "Ruta: {$fullPath}\n";
    
    if (!file_exists($fullPath)) {
        echo "❌ Archivo físico no existe, eliminando registro...\n";
        
        try {
            $video->delete();
            $cleaned++;
            echo "✅ Registro eliminado exitosamente\n";
        } catch (Exception $e) {
            echo "❌ Error al eliminar registro: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✅ Archivo físico existe\n";
    }
    
    echo "---\n";
}

echo "\n=== RESUMEN ===\n";
echo "Videos limpiados: {$cleaned}\n";

// Verificar artículos después de la limpieza
$articlesWithVideos = Article::whereHas('media', function ($query) {
    $query->where('collection_name', 'videos');
})->with(['media' => function ($query) {
    $query->where('collection_name', 'videos');
}])->get();

echo "Artículos con videos después de limpieza: " . $articlesWithVideos->count() . "\n";

foreach ($articlesWithVideos as $article) {
    echo "- {$article->title} (ID: {$article->id}): " . $article->getMedia('videos')->count() . " videos\n";
}
