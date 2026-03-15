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
        Schema::create('machine_section_machine_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_type_id')->constrained('machine_types')->cascadeOnDelete();
            $table->foreignId('machine_section_id')->constrained('machine_sections')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['machine_type_id', 'machine_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_type_machine_section');
    }
};
