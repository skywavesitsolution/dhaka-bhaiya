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
        Schema::create('walking_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_invoice_id')->nullable()->constrained('sale_invoices')->onDelete('cascade');
            $table->string('name')->default('Walking Customer');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walking_customers');
    }
};
