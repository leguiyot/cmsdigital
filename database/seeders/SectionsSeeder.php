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
                'name' => 'Política',
                'slug' => 'politica',
                'description' => 'Noticias y análisis político nacional e internacional',
                'color' => '#dc2626',
                'icon' => 'government',
                'order' => 1,
            ],
            [
                'name' => 'Economía',
                'slug' => 'economia',
                'description' => 'Información económica, mercados y finanzas',
                'color' => '#059669',
                'icon' => 'chart-line',
                'order' => 2,
            ],
            [
                'name' => 'Sociedad',
                'slug' => 'sociedad',
                'description' => 'Temas sociales, educación y cultura',
                'color' => '#7c3aed',
                'icon' => 'users',
                'order' => 3,
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Cobertura deportiva nacional e internacional',
                'color' => '#ea580c',
                'icon' => 'trophy',
                'order' => 4,
            ],
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
                'description' => 'Innovación, ciencia y tecnología',
                'color' => '#0284c7',
                'icon' => 'cpu-chip',
                'order' => 5,
            ],
            [
                'name' => 'Cultura',
                'slug' => 'cultura',
                'description' => 'Arte, literatura, música y espectáculos',
                'color' => '#be185d',
                'icon' => 'academic-cap',
                'order' => 6,
            ],
            [
                'name' => 'Internacional',
                'slug' => 'internacional',
                'description' => 'Noticias del mundo y política exterior',
                'color' => '#0891b2',
                'icon' => 'globe',
                'order' => 7,
            ],
            [
                'name' => 'Opinión',
                'slug' => 'opinion',
                'description' => 'Columnas de opinión y editoriales',
                'color' => '#ca8a04',
                'icon' => 'chat-bubble-left-right',
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
        $politica = Section::where('slug', 'politica')->first();
        if ($politica) {
            $subsecciones = [
                [
                    'name' => 'Gobierno',
                    'slug' => 'gobierno',
                    'description' => 'Noticias del gobierno nacional',
                    'parent_id' => $politica->id,
                    'order' => 1,
                ],
                [
                    'name' => 'Congreso',
                    'slug' => 'congreso',
                    'description' => 'Actividad legislativa',
                    'parent_id' => $politica->id,
                    'order' => 2,
                ],
            ];

            foreach ($subsecciones as $subseccion) {
                Section::firstOrCreate(
                    ['slug' => $subseccion['slug']],
                    $subseccion
                );
            }
        }

        $deportes = Section::where('slug', 'deportes')->first();
        if ($deportes) {
            $subseccionesDeportes = [
                [
                    'name' => 'Fútbol',
                    'slug' => 'futbol',
                    'description' => 'Fútbol nacional e internacional',
                    'parent_id' => $deportes->id,
                    'order' => 1,
                ],
                [
                    'name' => 'Otros Deportes',
                    'slug' => 'otros-deportes',
                    'description' => 'Deportes diversos',
                    'parent_id' => $deportes->id,
                    'order' => 2,
                ],
            ];

            foreach ($subseccionesDeportes as $subseccion) {
                Section::firstOrCreate(
                    ['slug' => $subseccion['slug']],
                    $subseccion
                );
            }
        }
    }
}
