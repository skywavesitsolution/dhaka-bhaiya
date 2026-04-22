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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('party_id')->nullable()->constrained('parties');
            $table->string('customer_reciveable')->nullable();
            $table->date('bill_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('total_bill');
            $table->string('payment_type');
            $table->string('discount_type')->nullable();
            $table->string('discount_value')->nullable();
            $table->string('discount_actual_value')->nullable();
            $table->decimal('service_charges', 10, 2)->nullable()->default(0);
            $table->string('status')->nullable();
            $table->string('order_type')->nullable();
            $table->string('net_payable');
            $table->string('total_gst')->nullable();    // total amount of GST applied on the full invoice
            $table->string('total_scheme_qty')->nullable(); // total_scheme_qty products
            $table->ulid('sale_batch_id')->nullable()->constrained('sale_batches')->onDelete('cascade'); // ulid id , primary id of the sale batches table
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('cascade');
            $table->string('ip_address');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};
