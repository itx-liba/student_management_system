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
    Schema::create('staff_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
        $table->date('attendance_date');
        $table->time('check_in')->nullable();
        $table->time('check_out')->nullable();
        $table->string('status');
        $table->integer('late_minutes')->default(0);
        $table->string('remarks')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('staff_attendances');
}
};
