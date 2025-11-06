<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;

/**
 * Comando para regenerar todas las conversiones de imágenes
 * Útil después de cambiar la configuración de media-library
 */
class RegenerateMediaConversions extends Command
{
    /**
     * Nombre y firma del comando
     *
     * @var string
     */
    protected $signature = 'media:regenerate {--queue : Ejecutar en cola en lugar de síncronamente}';

    /**
     * Descripción del comando
     *
     * @var string
     */
    protected $description = 'Regenera todas las conversiones de imágenes (thumbnails, medium, etc.)';

    /**
     * Ejecutar el comando
     */
    public function handle()
    {
        $useQueue = $this->option('queue');
        
        $this->info('🔄 Regenerando conversiones de imágenes...');
        
        // Obtener todos los archivos de medios
        $mediaFiles = Media::all();
        
        if ($mediaFiles->isEmpty()) {
            $this->warn('⚠️  No se encontraron archivos de medios.');
            return 0;
        }

        $this->info("📊 Regenerando conversiones para {$mediaFiles->count()} archivos.");
        
        $progressBar = $this->output->createProgressBar($mediaFiles->count());
        $progressBar->start();
        
        $regenerated = 0;
        $errors = 0;
        
        foreach ($mediaFiles as $media) {
            try {
                // Regenerar conversiones para cada archivo
                PerformConversionsJob::dispatch($media);
                
                $regenerated++;
                
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("❌ Error regenerando conversiones para {$media->file_name}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ REGENERACIÓN COMPLETADA:");
        $this->info("   - Conversiones regeneradas: {$regenerated}");
        $this->info("   - Errores: {$errors}");
        
        if ($useQueue) {
            $this->info("📋 Las conversiones se han puesto en cola. Ejecuta 'php artisan queue:work' para procesarlas.");
        }
        
        if ($errors === 0) {
            $this->info("🎉 ¡Todas las conversiones se regeneraron exitosamente!");
        }

        return 0;
    }
}
