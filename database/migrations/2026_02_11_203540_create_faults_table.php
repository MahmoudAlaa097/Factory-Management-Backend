<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\FaultStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('reported_by')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('maintenance_management_id')->constrained('managements')->cascadeOnDelete();
            $table->foreignId('maintenance_approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('status', array_column(FaultStatus::cases(), 'value'))->default(FaultStatus::Open->value);
            $table->text('description');
            $table->timestamp('reported_at');
            $table->timestamp('technician_started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('operator_accepted_at')->nullable();
            $table->timestamp('maintenance_approved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedInteger('time_consumed')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faults');
    }
};
