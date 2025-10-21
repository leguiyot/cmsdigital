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
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->text('html_content')->nullable(); // Para banners con HTML personalizado
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('location', ['header', 'sidebar', 'between_articles', 'footer', 'popup'])->default('sidebar');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('ctr', 5, 2)->default(0); // Click Through Rate
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->json('targeting')->nullable(); // Para targeting por sección, etc.
            $table->timestamps();

            $table->index(['location', 'is_active', 'priority']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};
