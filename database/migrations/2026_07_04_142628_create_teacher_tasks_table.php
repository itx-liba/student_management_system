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
    Schema::create('teacher_tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('deadline_at');
        $table->string('priority')->default('medium');
        $table->string('status')->default('pending');
        $table->text('completion_note')->nullable();
        $table->dateTime('completed_at')->nullable();
        $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('teacher_tasks');
}
};
