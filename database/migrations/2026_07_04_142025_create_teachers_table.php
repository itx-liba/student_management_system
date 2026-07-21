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
    Schema::create('teachers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('employee_no')->unique();
        $table->string('father_name')->nullable();
        $table->string('cnic')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->date('joining_date')->nullable();
        $table->string('photo')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('teachers');
}
};
