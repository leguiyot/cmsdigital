<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Comando para migrar imágenes existentes del storage al nuevo sistema de uploads
 * Similar al sistema de organización de WordPress
 */
class MigrateMediaToUploads extends Command
{
    /**
     * Nombre y firma del comando
     *
     * @var string
     */
    protected $signature = 'media:migrate-to-uploads {--dry-run : Solo mostrar lo que se haría sin ejecutar}';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Migra todas las imágenes del storage/app/public al nuevo sistema uploads/ organizado por fecha';

    /**
     * Ejecutar el comando
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 MODO PREVIEW - Solo mostrando lo que se haría (usa --dry-run=false para ejecutar)');
        }

        $this->info('📁 Iniciando migración de imágenes al sistema uploads/...');
        
        // Obtener todos los archivos de medios
        $mediaFiles = Media::all();
        
        if ($mediaFiles->isEmpty()) {
            $this->warn('⚠️  No se encontraron archivos de medios para migrar.');
            return 0;
        }

        $this->info("📊 Encontrados {$mediaFiles->count()} archivos de medios.");
        
        $migrated = 0;
        $errors = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                $this->migrateMediaFile($media, $dryRun);
                $migrated++;
                
                if ($migrated % 10 == 0) {
                    $this->info("📈 Procesados {$migrated} archivos...");
                }
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("❌ Error migrando {$media->file_name}: " . $e->getMessage());
            }
        }
        
        $this->newLine();
        
        if ($dryRun) {
            $this->info("🔍 PREVIEW COMPLETADO:");
            $this->info("   - Archivos que se migrarían: {$migrated}");
            $this->info("   - Errores detectados: {$errors}");
            $this->info("📋 Ejecuta sin --dry-run para realizar la migración real.");
        } else {
            $this->info("✅ MIGRACIÓN COMPLETADA:");
            $this->info("   - Archivos migrados: {$migrated}");
            $this->info("   - Errores: {$errors}");
            
            if ($errors === 0) {
                $this->info("🎉 ¡Todas las imágenes se migraron exitosamente al sistema uploads/!");
            }
        }

        return 0;
    }

    /**
     * Migra un archivo de medios individual
     */
    private function migrateMediaFile(Media $media, bool $dryRun = false): void
    {
        // Generar nueva ruta usando nuestro CustomPathGenerator
        $date = $media->created_at ?? now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $collection = $media->collection_name;
        
        // Construir varias rutas candidatas en storage donde el archivo podría estar
        $candidates = [
            storage_path("app/public/{$media->id}/{$media->file_name}"),
            storage_path("app/public/{$media->file_name}"),
            storage_path("app/public/{$media->model_type}/{$media->model_id}/{$media->file_name}"),
            storage_path("app/public/{$media->model_id}/{$media->file_name}"),
            storage_path("app/public/{$collection}/{$year}/{$month}/{$media->file_name}"),
            storage_path("app/public/articles/{$collection}/{$year}/{$month}/{$media->file_name}"),
        ];

        $oldPath = null;
        foreach ($candidates as $cand) {
            if (File::exists($cand)) {
                $oldPath = $cand;
                break;
            }
        }

        // Nueva ruta (uploads)
        $newDir = public_path("uploads/articles/{$collection}/{$year}/{$month}");
        $newPath = "{$newDir}/{$media->file_name}";
        
        $this->line("📄 {$media->file_name} -> articles/{$collection}/{$year}/{$month}/");
        
        if (!$dryRun) {
            // Crear directorio si no existe
            File::ensureDirectoryExists($newDir);
            
            // Copiar archivo si existe en alguna ruta candidata
            if ($oldPath && File::exists($oldPath)) {
                File::copy($oldPath, $newPath);
                
                // Actualizar registro en base de datos
                $media->update([
                    'disk' => 'uploads',
                ]);
                
                $this->line("   ✅ Copiado desde: {$oldPath} y actualizado en DB");
            } else {
                $this->line("   ⚠️  Archivo original no encontrado en storage (candidatos probados)");
            }
        }
    }
}
