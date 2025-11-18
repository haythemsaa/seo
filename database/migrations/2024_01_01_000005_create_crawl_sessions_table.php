<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crawl_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->enum('crawl_type', ['full', 'incremental', 'targeted'])->default('full');
            $table->integer('pages_crawled')->default(0);
            $table->integer('pages_total')->default(0);
            $table->integer('errors_count')->default(0);
            $table->integer('warnings_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('crawl_data')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('project_id');
            $table->index('status');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawl_sessions');
    }
};
