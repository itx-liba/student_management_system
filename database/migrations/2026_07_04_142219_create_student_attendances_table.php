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
    Schema::create('student_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
        $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
        $table->date('attendance_date');
        $table->string('status');
        $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
        $table->string('remarks')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('student_attendances');
}
};
