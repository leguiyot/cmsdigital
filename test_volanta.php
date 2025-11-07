<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Crear artículo con volanta
$article = App\Models\Article::create([
    'title' => 'Tecnología Médica Revolucionaria',
    'volanta' => 'CIENCIA Y SALUD',
    'excerpt' => 'Nuevo avance en medicina que promete cambiar el panorama de la salud.',
    'body' => 'El desarrollo de nuevas tecnologías médicas está transformando el panorama de la salud. Los investigadores han logrado importantes avances en el tratamiento de enfermedades que antes se consideraban incurables.',
    'section_id' => 2, // Economía
    'author_id' => 1,
    'status' => 'published',
    'published_at' => now(),
    'is_featured' => true,
    'featured_at' => now(),
    'allow_comments' => true,
    'reading_time' => 3
]);

echo "✅ Artículo creado exitosamente!\n";
echo "ID: " . $article->id . "\n";
echo "Título: " . $article->title . "\n";
echo "Volanta: " . $article->volanta . "\n";
echo "Sección: " . $article->section->name . "\n";
echo "URL: /articles/" . $article->slug . "\n";

echo "\n🎉 ¡Sistema de volanta funcionando correctamente!\n";
