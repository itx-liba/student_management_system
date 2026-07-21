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
    Schema::create('exam_marks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
        $table->decimal('total_marks', 6, 2);
        $table->decimal('obtained_marks', 6, 2);
        $table->string('remarks')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('exam_marks');
}
};
