<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('target_role')->nullable()->index(); // 'admin', 'tenant', 'buyer'
            $table->string('type')->index(); // 'new_store', 'order_created', 'order_paid', 'order_failed', 'payout'
            $table->string('title');
            $table->text('body');
            $table->string('icon')->default('notifications');
            $table->string('url')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
