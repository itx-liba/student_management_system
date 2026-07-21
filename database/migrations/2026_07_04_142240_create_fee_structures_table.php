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
    Schema::create('fee_structures', function (Blueprint $table) {
        $table->id();
        $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
        $table->string('title');
        $table->decimal('amount', 10, 2);
        $table->unsignedTinyInteger('due_day');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('fee_structures');
}
};
