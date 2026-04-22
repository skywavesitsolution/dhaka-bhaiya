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
        Schema::create('make_payment_items', function (Blueprint $table) {
            $table->id();
            $table->integer('make_payment_id');
            $table->string('particular');
            $table->integer('particular_id');
            $table->string('particular_name');
            $table->float('payment', 15);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('make_payment_items');
    }
};
