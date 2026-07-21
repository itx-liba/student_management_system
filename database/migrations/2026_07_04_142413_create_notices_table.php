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
    Schema::create('notices', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('body');
        $table->string('audience');
        $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
        $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
        $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('notices');
}
};
