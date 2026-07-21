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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('Information')->index(); // Critical, Warning, Information, Success, Prediction, Decision, Weather, Currency, Inflation, News, Port, System
            $table->string('priority')->default('Medium')->index(); // Critical, High, Medium, Low
            $table->string('category')->default('System')->index(); // Maritime, Economic, Geopolitical, Weather, Forecast, Operational, System
            $table->string('country')->nullable()->index();
            $table->string('status')->default('Active')->index(); // Active, Acknowledged, Resolved, Escalated
            $table->boolean('is_read')->default(false)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
