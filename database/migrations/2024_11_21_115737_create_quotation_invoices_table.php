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
        Schema::create('quotation_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('party_id')->nullable()->constrained('parties');
            $table->string('customer_reciveable')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_number')->nullable();
            $table->text('customer_address')->nullable();
            $table->enum('status', ['pending', 'inprocess', 'ready_to_serve', 'complete'])->default('pending');
            $table->date('bill_date')->nullable();
            $table->string('total_bill');
            $table->string('payment_type')->nullable();
            $table->string('discount_type')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('discount_actual_value')->nullable();
            $table->string('table_number')->nullable();
            $table->string('employee')->nullable();
            $table->string('order_type')->nullable();
            $table->string('adjustment')->nullable();
            $table->string('net_payable');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_invoices');
    }
};
