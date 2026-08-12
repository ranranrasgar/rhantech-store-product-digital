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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('handle');
            $table->string('avatar_url')->nullable();
            $table->string('followers_count');
            $table->string('clicks_count');
            $table->string('orders_count');
            $table->string('sales_range');
            $table->string('audience_demographic')->nullable();
            $table->string('platform');
            $table->json('categories')->nullable();
            $table->boolean('is_golden_tick')->default(false);
            $table->boolean('is_good_sample_completion')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
