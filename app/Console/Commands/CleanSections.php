<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Section;

class CleanSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sections:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar secciones no deseadas y mantener solo las secciones del nuevo menú';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Secciones que queremos mantener
        $sectionsToKeep = [
            'politica-local',
            'politica-nacional', 
            'policiales',
            'sociedad',
            'deportes',
            'economia',
            'valle-de-uco',
            'entretenimiento',
            'internacionales'
        ];

        // Secciones que queremos eliminar específicamente
        $sectionsToDelete = [
            'futbol',
            'gobierno', 
            'politica', // la antigua
            'congreso',
            'otros-deportes',
            'tecnologia',
            'cultura',
            'internacional', // la antigua (sin s)
            'opinion'
        ];

        $this->info('Eliminando secciones no deseadas...');

        // Eliminar secciones específicas
        foreach ($sectionsToDelete as $slug) {
            $section = Section::where('slug', $slug)->first();
            if ($section) {
                $this->line("Eliminando: {$section->name} ({$section->slug})");
                $section->delete();
            }
        }

        // También eliminar cualquier sección que no esté en la lista de mantener
        $allSections = Section::whereNotIn('slug', $sectionsToKeep)->get();
        foreach ($allSections as $section) {
            $this->line("Eliminando sección adicional: {$section->name} ({$section->slug})");
            $section->delete();
        }

        $this->info('Limpieza completada!');
        
        // Mostrar secciones restantes
        $this->info('Secciones restantes:');
        $remainingSections = Section::orderBy('order')->orderBy('name')->get();
        foreach ($remainingSections as $section) {
            $this->line("- {$section->name} ({$section->slug})");
        }

        return 0;
    }
}
