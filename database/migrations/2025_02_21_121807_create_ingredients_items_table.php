<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsItemsTable extends Migration
{
    public function up()
    {
        Schema::create('ingredients_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredients_id')->constrained('ingredients')->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->decimal('qty', 10, 5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredients_items');
    }
}
