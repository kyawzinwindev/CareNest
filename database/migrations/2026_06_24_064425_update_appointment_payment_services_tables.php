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
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('required_prepayment');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->text('prescription')->nullable()->after('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unique('appointment_id');
            $table->timestamp('paid_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['appointment_id']);
            $table->time('paid_at')->nullable(false)->change();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('payment_type')->after('time_slot_id');
            $table->dropColumn('prescription');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('required_prepayment')->default(false);
        });
    }
};
