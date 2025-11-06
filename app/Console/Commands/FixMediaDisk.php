<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FixMediaDisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:fix-disk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix media records without disk specified';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando todos los archivos de medios para usar el disco "uploads"...');
        
        // Actualizar todos los registros de media para usar el disco 'uploads'
        $count = Media::query()->update(['disk' => 'uploads']);
        
        $this->info("Se actualizaron {$count} archivos de medios.");
        
        // Verificar algunos registros
        $this->info('Verificando algunos registros:');
        $samples = Media::take(5)->get(['id', 'file_name', 'disk']);
        foreach ($samples as $media) {
            $this->line("ID: {$media->id}, Archivo: {$media->file_name}, Disco: {$media->disk}");
        }
        
        $this->info('¡Corrección completada!');
    }
}
