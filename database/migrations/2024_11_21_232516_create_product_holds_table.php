<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Stmt\Case_;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('invoice_hold_id')->constrained('product_hold_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_variants');
            $table->string('retail_price');
            $table->string('sale_qty');
            $table->string('dicount_type')->nullable();
            $table->string('dicount_value')->nullable();
            $table->string('sale_amount');
            $table->string('location')->nullable();
            $table->string('remarks')->nullable();
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_holds');
    }
};
