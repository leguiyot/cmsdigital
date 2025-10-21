<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('featured_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade');
            $table->string('position'); // 'hero', 'sidebar', 'banner', 'carousel'
            $table->enum('style', ['large', 'medium', 'small', 'carousel', 'grid'])->default('medium');
            $table->json('settings')->nullable(); // Para configuraciones adicionales como colores, etc.
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->index(['position', 'active', 'order']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_blocks');
    }
};
