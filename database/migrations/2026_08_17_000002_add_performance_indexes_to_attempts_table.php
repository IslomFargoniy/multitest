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
        Schema::table('attempts', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'attempts_user_created_idx');
            $table->index(['mock_id', 'created_at'], 'attempts_mock_created_idx');
            $table->index(['test_id', 'created_at'], 'attempts_test_created_idx');
            $table->index('evaluated_at', 'attempts_evaluated_at_idx');
            $table->index('created_at', 'attempts_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->dropIndex('attempts_user_created_idx');
            $table->dropIndex('attempts_mock_created_idx');
            $table->dropIndex('attempts_test_created_idx');
            $table->dropIndex('attempts_evaluated_at_idx');
            $table->dropIndex('attempts_created_at_idx');
        });
    }
};
