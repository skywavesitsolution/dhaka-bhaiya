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
        Schema::create('party_ledgers', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('party_id');
            $table->string('party_type');
            $table->float('payment', 15)->nullable();
            $table->float('received', 15)->nullable();
            $table->float('price', 15)->nullable();
            $table->float('balance', 15);
            $table->integer('sale_id')->nullable();
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('cascade');
            $table->integer('payment_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('recevied_id')->nullable();
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
        Schema::dropIfExists('party_ledgers');
    }
};
