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
    Schema::create('grade_rules', function (Blueprint $table) {
        $table->id();
        $table->string('grade');
        $table->decimal('min_percentage', 5, 2);
        $table->decimal('max_percentage', 5, 2);
        $table->string('remarks')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('grade_rules');
}
};
