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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('credit_balance', 15, 2)->default(0)->after('avatar');
            $table->timestamp('credit_expires_at')->nullable()->after('credit_balance');
            $table->boolean('is_new_member_credit_claimed')->default(false)->after('credit_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['credit_balance', 'credit_expires_at', 'is_new_member_credit_claimed']);
        });
    }
};
