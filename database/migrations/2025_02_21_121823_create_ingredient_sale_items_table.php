<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientSaleItemsTable extends Migration
{
    public function up()
    {
        Schema::create('ingredient_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_sale_id')->constrained('ingredient_sales')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('product_variants')->onDelete('cascade');
            $table->decimal('qty', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredient_sale_items');
    }
}