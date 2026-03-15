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
        Schema::create('component_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fault_id')->constrained('faults')->cascadeOnDelete();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('old_component_id')->constrained('machine_components')->cascadeOnDelete();
            $table->foreignId('new_component_id')->constrained('machine_components')->cascadeOnDelete();
            $table->foreignId('replaced_by')->constrained('employees')->cascadeOnDelete();
            $table->timestamp('replaced_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_replacements');
    }
};
