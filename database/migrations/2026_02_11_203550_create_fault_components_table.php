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
        Schema::create('fault_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fault_id')->constrained('faults')->cascadeOnDelete();
            $table->foreignId('machine_component_id')->constrained('machine_components')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['fault_id', 'machine_component_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fault_components');
    }
};
