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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('doctor_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('service_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('time_slot_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('payment_type');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
