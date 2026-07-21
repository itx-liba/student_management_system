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
    Schema::create('fee_invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
        $table->string('invoice_no')->unique();
        $table->string('month');
        $table->integer('year');
        $table->decimal('amount', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('fine', 10, 2)->default(0);
        $table->decimal('paid_amount', 10, 2)->default(0);
        $table->date('due_date');
        $table->string('status')->default('unpaid');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('fee_invoices');
}
};
