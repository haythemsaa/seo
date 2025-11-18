<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('url', 2000);
            $table->string('target_keyword', 500)->nullable();
            $table->integer('content_score')->nullable();
            $table->integer('seo_score')->nullable();
            $table->integer('readability_score')->nullable();
            $table->json('recommendations')->nullable();
            $table->json('keyword_density')->nullable();
            $table->json('semantic_keywords')->nullable();
            $table->json('competitors_analysis')->nullable();
            $table->timestamp('analyzed_at')->useCurrent();

            $table->index('project_id');
            $table->index('target_keyword');
            $table->index(['url' => 255]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_analyses');
    }
};
