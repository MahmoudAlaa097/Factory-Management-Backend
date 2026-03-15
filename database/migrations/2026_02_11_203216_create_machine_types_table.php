<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machine_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->json('specifications')->nullable();
            $table->string('manual_url')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index('division_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_types');
    }
};
