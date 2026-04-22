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
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('updated_id')->nullable();
            $table->date('date')->nullable();
            $table->float('payment', 15)->nullable();
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('cascade');
            $table->foreignId('sale_id')->nullable()->constrained('sale_invoices')->onDelete('cascade');
            $table->float('received', 15)->nullable();
            $table->float('balance', 15);
            $table->integer('deposit_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('sub_payment_id')->nullable();
            $table->integer('sub_recevied_payment_id')->nullable();
            $table->integer('received_id')->nullable();
            $table->integer('expense_id')->nullable();
            $table->integer('account_id');
            $table->integer('day_close_id')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_ledgers');
    }
};
