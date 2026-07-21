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
    Schema::create('sms_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
        $table->string('phone');
        $table->text('message');
        $table->string('type')->nullable();
        $table->string('status')->nullable();
        $table->dateTime('sent_at')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('sms_logs');
}
};
