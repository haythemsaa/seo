<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['technical', 'content', 'backlink', 'local', 'general']);
            $table->enum('priority', ['critical', 'high', 'medium', 'low']);
            $table->string('title');
            $table->text('description');
            $table->integer('impact_score')->nullable();
            $table->integer('effort_score')->nullable();
            $table->json('affected_urls')->nullable();
            $table->json('action_items')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'dismissed'])->default('pending');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->index('project_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};
