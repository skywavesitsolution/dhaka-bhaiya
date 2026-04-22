<?php

use App\Enums\GstTypeEnum;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('invoice_id')->constrained('sale_invoices');
            $table->foreignId('product_id')->constrained('product_variants');
            $table->string('retail_price');
            $table->string('sale_qty');
            $table->string('sale_discount_type')->nullable();
            $table->string('sale_discount_value')->nullable();
            $table->string('sale_discount_actual_value')->nullable();
            $table->string('sale_amount');
            $table->string('location')->nullable();
            $table->foreignId('product_location_id')->nullable()->constrained('product_locations');
            $table->enum('gst_type', [
                GstTypeEnum::EXCLUDE->value,
                GstTypeEnum::INCLUDE->value,
            ])->default(GstTypeEnum::INCLUDE->value);
            $table->string('gst_percentage')->nullable();    // total amount of GST applied on the this specific product
            $table->string('gst_amount')->nullable();    // total amount of GST applied on the this specific product
            $table->string('scheme_qty')->nullable(); // total_scheme_qty products
            $table->string('remarks')->nullable();
            $table->string('ip_address');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_products');
    }
};
