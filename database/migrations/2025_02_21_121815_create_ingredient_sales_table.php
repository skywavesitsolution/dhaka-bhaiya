<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientSalesTable extends Migration
{
    public function up()
    {
        Schema::create('ingredient_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sale_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_variants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredient_sales');
    }
}