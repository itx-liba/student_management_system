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
    Schema::create('admission_applications', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('father_name');
        $table->string('b_form')->nullable();
        $table->string('phone');
        $table->string('previous_school')->nullable();
        $table->foreignId('desired_class_id')->nullable()->constrained('classes')->nullOnDelete();
        $table->text('address')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('admission_applications');
}
};
