<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crawl_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('url', 2000);
            $table->char('url_hash', 64);
            $table->integer('status_code')->nullable();
            $table->string('content_type', 100)->nullable();
            $table->integer('page_size')->nullable();
            $table->integer('load_time')->nullable();
            $table->string('title', 500)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('h1')->nullable();
            $table->string('canonical_url', 2000)->nullable();
            $table->string('robots_meta')->nullable();
            $table->integer('word_count')->nullable();
            $table->integer('internal_links_count')->nullable();
            $table->integer('external_links_count')->nullable();
            $table->integer('images_count')->nullable();
            $table->boolean('has_https')->nullable();
            $table->boolean('is_indexable')->nullable();
            $table->json('issues')->nullable();
            $table->char('content_hash', 64)->nullable();
            $table->timestamp('crawled_at')->useCurrent();

            $table->index('crawl_session_id');
            $table->index('project_id');
            $table->index('url_hash');
            $table->index('status_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawled_pages');
    }
};
