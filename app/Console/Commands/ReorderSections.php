<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Section;

class ReorderSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sections:reorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reordenar secciones según el orden específico del menú principal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Orden específico solicitado
        $sectionsOrder = [
            'politica-local' => 1,
            'politica-nacional' => 2,
            'policiales' => 3,
            'sociedad' => 4,
            'deportes' => 5,
            'economia' => 6,
            'valle-de-uco' => 7,
            'entretenimiento' => 8,
            'internacionales' => 9, // subsección de sociedad
        ];

        $this->info('Reordenando secciones...');

        foreach ($sectionsOrder as $slug => $order) {
            $section = Section::where('slug', $slug)->first();
            if ($section) {
                $section->update(['order' => $order]);
                $this->line("✓ {$section->name} - Orden: {$order}");
            } else {
                $this->error("✗ No se encontró la sección: {$slug}");
            }
        }

        // Asegurarse de que Internacionales sea subsección de Sociedad
        $sociedad = Section::where('slug', 'sociedad')->first();
        $internacionales = Section::where('slug', 'internacionales')->first();
        
        if ($sociedad && $internacionales) {
            $internacionales->update([
                'parent_id' => $sociedad->id,
                'show_in_menu' => true
            ]);
            $this->info("✓ Internacionales configurado como subsección de Sociedad");
        }

        $this->info('Reordenamiento completado!');
        
        // Mostrar el orden final
        $this->info('Orden final de secciones:');
        $sections = Section::whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->get();
        
        foreach ($sections as $section) {
            $this->line("{$section->order}. {$section->name}");
            
            // Mostrar subsecciones si las hay
            $subsections = Section::where('parent_id', $section->id)
                ->orderBy('order')
                ->get();
            foreach ($subsections as $subsection) {
                $this->line("   └─ {$subsection->name}");
            }
        }

        return 0;
    }
}
