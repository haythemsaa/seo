<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keyword_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keyword_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('search_engine', 50)->default('google');
            $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->default('desktop');
            $table->string('location', 100)->nullable();
            $table->integer('position')->nullable();
            $table->string('url', 1000)->nullable();
            $table->boolean('featured_snippet')->default(false);
            $table->boolean('local_pack')->default(false);
            $table->json('serp_features')->nullable();
            $table->timestamp('checked_at')->useCurrent();

            $table->index('keyword_id');
            $table->index('project_id');
            $table->index('checked_at');
            $table->index('position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyword_rankings');
    }
};
