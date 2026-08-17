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
        Schema::table('mocks', function (Blueprint $table) {
            if (!Schema::hasColumn('mocks', 'test_id')) {
                $table->foreignId('test_id')->nullable()->after('user_id')->constrained('tests')->onDelete('cascade');
            }
            if (!Schema::hasColumn('mocks', 'comment')) {
                $table->text('comment')->nullable()->after('name');
            }
            if (!Schema::hasColumn('mocks', 'started_at')) {
                $table->dateTime('started_at')->nullable()->after('comment');
            }
        });

        if (!Schema::hasTable('mock_students')) {
            Schema::create('mock_students', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mock_id')->constrained('mocks')->onDelete('cascade');
                $table->string('name');
                $table->string('code')->unique();
                $table->boolean('attended')->default(false);
                $table->string('phone')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('attempts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            if (!Schema::hasColumn('attempts', 'mock_student_id')) {
                $table->foreignId('mock_student_id')->nullable()->after('mock_id')->constrained('mock_students')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            if (Schema::hasColumn('attempts', 'mock_student_id')) {
                $table->dropForeign(['mock_student_id']);
                $table->dropColumn('mock_student_id');
            }
        });

        Schema::dropIfExists('mock_students');

        Schema::table('mocks', function (Blueprint $table) {
            if (Schema::hasColumn('mocks', 'test_id')) {
                $table->dropForeign(['test_id']);
                $table->dropColumn('test_id');
            }
            if (Schema::hasColumn('mocks', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('mocks', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};
