<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Ruta de prueba
Route::get('/test', [TestController::class, 'test'])->name('test');

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articulos/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/seccion/{slug}', [SectionController::class, 'show'])->name('sections.show');

// Rutas administrativas (requieren autenticación)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de artículos
    Route::resource('admin/articles', ArticleController::class)->except(['show'])->names([
        'index' => 'admin.articles.index',
        'create' => 'admin.articles.create',
        'store' => 'admin.articles.store',
        'edit' => 'admin.articles.edit',
        'update' => 'admin.articles.update',
        'destroy' => 'admin.articles.destroy',
    ]);
    
    // Gestión de secciones
    Route::resource('admin/sections', SectionController::class)->except(['show'])->names([
        'index' => 'admin.sections.index',
        'create' => 'admin.sections.create',
        'store' => 'admin.sections.store',
        'edit' => 'admin.sections.edit',
        'update' => 'admin.sections.update',
        'destroy' => 'admin.sections.destroy',
    ]);
    
    // Rutas adicionales para artículos
    Route::post('/admin/articles/{article}/publish', [ArticleController::class, 'publish'])->name('admin.articles.publish');
    Route::post('/admin/articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('admin.articles.unpublish');
    Route::post('/admin/articles/{article}/feature', [ArticleController::class, 'feature'])->name('admin.articles.feature');
});

// Rutas de perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
