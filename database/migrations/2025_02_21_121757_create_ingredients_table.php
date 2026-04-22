<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->decimal('ingredient_total_cost', 10, 2);
            $table->timestamps();
            $table->unique('product_variant_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}