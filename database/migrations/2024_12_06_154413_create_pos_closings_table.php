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
        Schema::create('pos_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('report_date'); // To store the report date
            $table->decimal('cash_submited', 10, 2)->nullable(); // Cash Submitted
            $table->decimal('system_cash', 10, 2)->default(0); // System Cash
            $table->decimal('customer_payments', 10, 2)->default(0); // Customer Payments
            $table->decimal('total_cash', 10, 2)->default(0); // Total Cash
            $table->decimal('phisical_cash', 10, 2)->default(0); // Physical Cash
            $table->decimal('differ_cash', 10, 2)->default(0); // Cash Difference
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_closings');
    }
};
