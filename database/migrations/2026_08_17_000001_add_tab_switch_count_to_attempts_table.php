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
            if (!Schema::hasColumn('attempts', 'tab_switch_count')) {
                $table->integer('tab_switch_count')->default(0)->after('score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            if (Schema::hasColumn('attempts', 'tab_switch_count')) {
                $table->dropColumn('tab_switch_count');
            }
        });
    }
};
