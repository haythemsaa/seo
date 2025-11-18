<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_console_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('query', 500);
            $table->string('page', 2000);
            $table->date('date');
            $table->integer('clicks')->default(0);
            $table->integer('impressions')->default(0);
            $table->decimal('ctr', 5, 4)->default(0);
            $table->decimal('position', 5, 2)->default(0);
            $table->string('device', 20)->nullable();
            $table->string('country', 5)->nullable();
            $table->timestamps();

            $table->index(['project_id', 'date']);
            $table->index('query');
            $table->unique(['project_id', 'query', 'page', 'date'], 'unique_metric');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_console_metrics');
    }
};
