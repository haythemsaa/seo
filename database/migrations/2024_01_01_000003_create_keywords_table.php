<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('keyword', 500);
            $table->integer('search_volume')->default(0);
            $table->decimal('cpc', 10, 2)->default(0);
            $table->decimal('competition', 3, 2)->default(0);
            $table->integer('difficulty_score')->default(0);
            $table->enum('search_intent', ['informational', 'navigational', 'commercial', 'transactional'])->nullable();
            $table->foreignId('cluster_id')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('project_id');
            $table->index('keyword');
            $table->index('cluster_id');
            $table->unique(['project_id', 'keyword']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
