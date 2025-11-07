<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG HOMECONTROLLER ===\n";

// Simular exactamente lo que hace HomeController::index()
use App\Models\Article;
use App\Models\Section;

echo "1. Artículos destacados publicados:\n";
$featuredArticles = Article::where('status', 'published')
    ->where('is_featured', true)
    ->with(['author', 'section'])
    ->orderBy('featured_at', 'desc')
    ->orderBy('published_at', 'desc')
    ->take(5)
    ->get();

foreach($featuredArticles as $article) {
    echo "   - {$article->title} (Status: {$article->status})\n";
}

echo "\n2. Secciones activas:\n";
$sections = Section::where('is_active', true)
    ->with(['children' => function($query) {
        $query->where('is_active', true)->orderBy('order')->orderBy('name');
    }])
    ->orderBy('order')
    ->orderBy('name')
    ->get();

echo "   Encontradas: " . $sections->count() . " secciones\n";

echo "\n3. Últimos artículos por sección:\n";
$latestBySection = [];
foreach ($sections->whereNull('parent_id')->take(6) as $section) {
    $sectionArticles = Article::where('status', 'published')
        ->where('section_id', $section->id)
        ->with(['author'])
        ->orderBy('published_at', 'desc')
        ->take(4)
        ->get();
    
    $latestBySection[$section->slug] = $sectionArticles;
    
    echo "   Sección '{$section->name}' ({$section->slug}): {$sectionArticles->count()} artículos\n";
    foreach($sectionArticles as $article) {
        echo "      - {$article->title} (Status: {$article->status})\n";
    }
}

echo "\n4. Artículos más leídos:\n";
$mostRead = Article::where('status', 'published')
    ->with(['author', 'section'])
    ->where('published_at', '>=', now()->subWeek())
    ->orderBy('views_count', 'desc')
    ->take(5)
    ->get();

foreach($mostRead as $article) {
    echo "   - {$article->title} (Status: {$article->status})\n";
}

echo "\n5. Últimas noticias:\n";
$latestNews = Article::where('status', 'published')
    ->with(['author', 'section'])
    ->orderBy('published_at', 'desc')
    ->take(10)
    ->get();

foreach($latestNews as $article) {
    echo "   - {$article->title} (Status: {$article->status})\n";
}

echo "\n=== FIN DEBUG ===\n";
