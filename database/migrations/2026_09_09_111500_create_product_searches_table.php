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
        Schema::create('product_searches', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 100)->unique()->index();
            $table->unsignedBigInteger('hits')->default(1)->index();
            $table->unsignedInteger('results_count')->default(0);
            $table->timestamp('last_searched_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_searches');
    }
};
