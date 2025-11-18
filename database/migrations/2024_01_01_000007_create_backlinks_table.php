<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backlinks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('source_url', 2000);
            $table->string('source_domain');
            $table->string('target_url', 2000);
            $table->text('anchor_text')->nullable();
            $table->enum('link_type', ['dofollow', 'nofollow', 'ugc', 'sponsored'])->default('dofollow');
            $table->integer('domain_authority')->nullable();
            $table->integer('page_authority')->nullable();
            $table->integer('trust_flow')->nullable();
            $table->integer('citation_flow')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamp('lost_at')->nullable();
            $table->timestamps();

            $table->index('project_id');
            $table->index('source_domain');
            $table->index(['target_url' => 255]);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backlinks');
    }
};
