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
        Schema::create('kitchen_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('quotation_invoice_id')->nullable();
            $table->foreignId('party_id')->nullable()->constrained('parties');
            $table->string('customer_reciveable')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_number')->nullable();
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
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['in_kitchen', 'ready_to_serve', 'complete'])->default('in_kitchen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_invoices');
    }
};
