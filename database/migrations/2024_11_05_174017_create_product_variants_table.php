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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('product_variant_name');
            $table->string('product_variant_urdu_name')->nullable();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained('product_sizes')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('is_fixed_asset')->default(false);
            $table->string('SKU')->unique();
            $table->foreignId('measuring_unit_id')->constrained('measuring_units')->onDelete('cascade');
            $table->integer('min_order_qty')->default(1);
            $table->integer('manage_deal_items')->default(0);
            $table->boolean('service_item')->default(false);
            $table->boolean('finish_goods')->default(false);
            $table->boolean('raw_material')->default(false);
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
        Schema::dropIfExists('product_variants');
    }
};
