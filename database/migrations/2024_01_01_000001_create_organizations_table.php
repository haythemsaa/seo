<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->enum('subscription_plan', ['free', 'starter', 'professional', 'agency', 'enterprise'])->default('free');
            $table->enum('subscription_status', ['active', 'cancelled', 'expired', 'suspended'])->default('active');
            $table->timestamp('subscription_ends_at')->nullable();
            $table->boolean('white_label_enabled')->default(false);
            $table->json('white_label_config')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('owner_id');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
