<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'Política Local',
                'slug' => 'politica-local',
                'description' => 'Noticias de política municipal y regional',
                'color' => '#dc2626',
                'icon' => 'building-office',
                'order' => 1,
            ],
            [
                'name' => 'Política Nacional',
                'slug' => 'politica-nacional',
                'description' => 'Noticias y análisis político nacional',
                'color' => '#b91c1c',
                'icon' => 'government',
                'order' => 2,
            ],
            [
                'name' => 'Policiales',
                'slug' => 'policiales',
                'description' => 'Noticias policiales y de seguridad',
                'color' => '#1f2937',
                'icon' => 'shield-check',
                'order' => 3,
            ],
            [
                'name' => 'Sociedad',
                'slug' => 'sociedad',
                'description' => 'Temas sociales, educación y cultura',
                'color' => '#7c3aed',
                'icon' => 'users',
                'order' => 4,
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Cobertura deportiva nacional e internacional',
                'color' => '#ea580c',
                'icon' => 'trophy',
                'order' => 5,
            ],
            [
                'name' => 'Economía',
                'slug' => 'economia',
                'description' => 'Información económica, mercados y finanzas',
                'color' => '#059669',
                'icon' => 'chart-line',
                'order' => 6,
            ],
            [
                'name' => 'Valle de Uco',
                'slug' => 'valle-de-uco',
                'description' => 'Noticias locales del Valle de Uco',
                'color' => '#16a34a',
                'icon' => 'map',
                'order' => 7,
            ],
            [
                'name' => 'Entretenimiento',
                'slug' => 'entretenimiento',
                'description' => 'Espectáculos, cultura y entretenimiento',
                'color' => '#be185d',
                'icon' => 'film',
                'order' => 8,
            ],
        ];

        foreach ($sections as $sectionData) {
            Section::firstOrCreate(
                ['slug' => $sectionData['slug']],
                $sectionData
            );
        }

        // Crear subsecciones para algunas secciones principales
        $sociedad = Section::where('slug', 'sociedad')->first();
        if ($sociedad) {
            $subsecciones = [
                [
                    'name' => 'Internacionales',
                    'slug' => 'internacionales',
                    'description' => 'Noticias del mundo y política exterior',
                    'parent_id' => $sociedad->id,
                    'color' => '#0891b2',
                    'icon' => 'globe',
                    'order' => 1,
                ],
            ];

            foreach ($subsecciones as $subseccion) {
                Section::firstOrCreate(
                    ['slug' => $subseccion['slug']],
                    $subseccion
                );
            }
        }
    }
}
