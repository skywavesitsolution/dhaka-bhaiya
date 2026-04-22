<?php

use App\Models\Sales\SaleInvoice;
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
        Schema::create('product_variant_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('updated_id')->nullable();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            // $table->foreignId('product_variant_order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_variant_sale_id')->nullable()->constrained('sale_invoices')->onDelete('set null');
            $table->foreignId('product_variant_purchase_id')->nullable()->constrained('purchases')->onDelete('set null');
            // $table->string('product_variant_order_stock')->default(0);
            $table->string('product_variant_sale_stock')->default(0);
            $table->string('product_variant_purchase_stock')->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_ledgers');
    }
};
