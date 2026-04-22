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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('received_date');
            $table->date('due_date');
            $table->string('status')->nullable();
            $table->foreignId('supplier_id')
            ->constrained('parties')
            ->onDelete('cascade');
            $table->string('supplier_balance');
            $table->string('payment_type');
            $table->string('payment_amount')->nullable();
            $table->foreignId('account_id')
            ->nullable()  // Make the column nullable
            ->constrained('accounts')  // Reference the 'accounts' table
            ->onDelete('cascade');  // Set cascade delete
            $table->string('total_bill');
            $table->string('adjustment')->nullable();
            $table->string('net_payable');
            $table->enum('order_status', ['pending', 'received'])->default('pending');
            $table->date('order_received_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
