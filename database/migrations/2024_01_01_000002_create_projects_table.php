<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('website_url', 500);
            $table->string('main_domain');
            $table->char('country_code', 2)->default('FR');
            $table->char('language_code', 5)->default('fr-FR');
            $table->json('search_engines')->nullable();
            $table->json('competitors')->nullable();
            $table->string('google_analytics_id', 50)->nullable();
            $table->string('google_search_console_property')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('crawl_frequency', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->timestamp('last_crawled_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('organization_id');
            $table->index('main_domain');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
