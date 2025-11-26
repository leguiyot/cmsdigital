<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Agrega índices a columnas frecuentemente consultadas para mejorar el rendimiento.
     * Usa try-catch para evitar errores si los índices ya existen.
     */
    public function up(): void
    {
        // Artículos
        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_status (status)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_is_featured (is_featured)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_published_at (published_at)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_section_id (section_id)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_slug (slug)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_published_featured (status, is_featured, published_at)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE articles ADD INDEX idx_articles_section_status (section_id, status, published_at)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        // Secciones
        try {
            DB::statement('ALTER TABLE sections ADD INDEX idx_sections_is_active (is_active)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE sections ADD INDEX idx_sections_slug (slug)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE sections ADD INDEX idx_sections_order (`order`)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE sections ADD INDEX idx_sections_parent_id (parent_id)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        // Comentarios
        try {
            DB::statement('ALTER TABLE comments ADD INDEX idx_comments_article_id (article_id)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE comments ADD INDEX idx_comments_is_approved (is_approved)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE comments ADD INDEX idx_comments_parent_id (parent_id)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }

        try {
            DB::statement('ALTER TABLE comments ADD INDEX idx_comments_article_approved (article_id, is_approved, created_at)');
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('idx_articles_status');
            $table->dropIndex('idx_articles_is_featured');
            $table->dropIndex('idx_articles_published_at');
            $table->dropIndex('idx_articles_section_id');
            $table->dropIndex('idx_articles_slug');
            $table->dropIndex('idx_articles_published_featured');
            $table->dropIndex('idx_articles_section_status');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex('idx_sections_is_active');
            $table->dropIndex('idx_sections_slug');
            $table->dropIndex('idx_sections_order');
            $table->dropIndex('idx_sections_parent_id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex('idx_comments_article_id');
            $table->dropIndex('idx_comments_is_approved');
            $table->dropIndex('idx_comments_parent_id');
            $table->dropIndex('idx_comments_article_approved');
        });
    }
};
