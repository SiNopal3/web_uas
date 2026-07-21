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
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
        });

        if (!Schema::hasTable('positive_words')) {
            Schema::create('positive_words', function (Blueprint $table) {
                $table->id();
                $table->string('word')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('negative_words')) {
            Schema::create('negative_words', function (Blueprint $table) {
                $table->id();
                $table->string('word')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });

        Schema::dropIfExists('positive_words');
        Schema::dropIfExists('negative_words');
    }
};
