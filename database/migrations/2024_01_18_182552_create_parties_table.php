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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Supplier', 'Customer', 'Both'])->default('Supplier');
            $table->string('supplier_id')->nullable();
            $table->float('opening_balance', 15)->default(0);
            $table->float('balance', 15)->default(0);
            $table->string('email')->unique()->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
