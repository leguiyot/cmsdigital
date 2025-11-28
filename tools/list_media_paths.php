<?php
// Lista rutas y discos de registros de Spatie Media para diagnóstico
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\MediaLibrary\MediaCollections\Models\Media;

$medias = Media::all();
foreach ($medias as $m) {
    $id = $m->id;
    $disk = $m->disk;
    $file = $m->file_name;
    $model = $m->model_type;
    $modelId = $m->model_id;
    $created = $m->created_at;
    $paths = [];

    // Try common locations
    $paths[] = storage_path("app/public/{$id}/{$file}");
    $paths[] = storage_path("app/public/{$file}");
    $paths[] = storage_path("app/public/{$model}/{$modelId}/{$file}");
    $paths[] = storage_path("app/public/{$modelId}/{$file}");

    // If media model has getPath method, try that too
    if (method_exists($m, 'getPath')) {
        try {
            $paths[] = $m->getPath();
        } catch (Throwable $e) {
            // ignore
        }
    }

    echo "ID: {$id} | disk: {$disk} | file: {$file} | model: {$model}:{$modelId}" . PHP_EOL;
    foreach ($paths as $p) {
        echo "  - candidate: {$p} -> " . (file_exists($p) ? 'FOUND' : 'MISSING') . PHP_EOL;
    }
    echo PHP_EOL;
}
