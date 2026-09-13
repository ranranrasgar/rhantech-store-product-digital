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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('status')->default('active')->after('store_mode')->index();
            $table->text('ban_reason')->nullable()->after('status');
            $table->timestamp('banned_at')->nullable()->after('ban_reason');
            $table->foreignId('banned_by')->nullable()->after('banned_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['banned_by']);
            $table->dropColumn(['status', 'ban_reason', 'banned_at', 'banned_by']);
        });
    }
};
