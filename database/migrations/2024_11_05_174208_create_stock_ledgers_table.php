<?php

use App\Enums\StockTransferTypeEnum;
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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->nullable();
            $table->foreignId('product_variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('product_locations')->onDelete('cascade');
            $table->string('qty');
            $table->string('stock_after_transaction');
            $table->enum('transfer_type', [
                StockTransferTypeEnum::IN->value,
                StockTransferTypeEnum::OUT->value,
                StockTransferTypeEnum::IMPORT->value,
                StockTransferTypeEnum::ADJUSTMENT->value,
            ]);
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
        Schema::dropIfExists('stock_ledgers');
    }
};
