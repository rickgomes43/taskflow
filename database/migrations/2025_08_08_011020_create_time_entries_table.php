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
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_running')->default(false);
            $table->date('date');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'date']);
            $table->index(['project_id', 'date']);
            $table->index(['is_running']);
            $table->index(['is_billable']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
