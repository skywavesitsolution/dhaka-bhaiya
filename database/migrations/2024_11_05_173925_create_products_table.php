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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_urdu_name')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_manage_variants')->default(false);
            $table->boolean('is_fixed_asset')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('new_arrival')->default(false);
            $table->boolean('best_selling_product')->default(false);
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
