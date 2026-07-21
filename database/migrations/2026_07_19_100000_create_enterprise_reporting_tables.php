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
        if (!Schema::hasTable('scheduled_reports')) {
            Schema::create('scheduled_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('report_type'); // Executive Report, Country Report, etc.
                $table->string('frequency'); // Daily, Weekly, Monthly, Quarterly, Annual
                $table->json('parameters')->nullable(); // Filters, selected KPIs, charts
                $table->string('recipients')->nullable(); // Email addresses or roles
                $table->timestamp('next_run_at')->nullable();
                $table->string('status')->default('active'); // active, paused
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('report_history')) {
            Schema::create('report_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('report_type');
                $table->string('title');
                $table->string('file_format'); // PDF, Excel, CSV, PNG, Print
                $table->float('file_size_kb')->default(0);
                $table->integer('download_count')->default(1);
                $table->json('parameters')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('export_logs')) {
            Schema::create('export_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('report_type');
                $table->string('format'); // PDF, Excel, CSV, PNG, Print
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('status')->default('SUCCESS'); // SUCCESS, FAILED
                $table->float('execution_time_ms')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_logs');
        Schema::dropIfExists('report_history');
        Schema::dropIfExists('scheduled_reports');
    }
};
