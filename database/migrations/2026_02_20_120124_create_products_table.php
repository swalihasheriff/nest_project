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
            $table->string('description');
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();

            // Barcodes
            $table->string('ctn_barcode')->nullable();
            $table->string('upc')->nullable();
            $table->string('product_barcode1')->nullable();
            $table->string('product_barcode2')->nullable();
            $table->string('product_barcode3')->nullable();

            // Codes
            $table->string('product_code')->nullable();
            $table->string('supplier_code')->nullable();

            // Prices
            $table->decimal('ctn_cost_price', 10, 2)->default(0);
            $table->decimal('gst', 5, 2)->default(0);
            $table->decimal('ctn_sell_price', 10, 2)->default(0);
            $table->decimal('sell_price2', 10, 2)->nullable();
            $table->decimal('sell_price3', 10, 2)->nullable();
            $table->decimal('sell_price4', 10, 2)->nullable();
            $table->decimal('sell_price5', 10, 2)->nullable();

            // Stock
            $table->integer('stock_on_hand')->default(0);
            $table->integer('minimum_threshold')->default(0);
            $table->integer('shelf_capacity')->nullable();

            // Location & size
            $table->string('location')->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('uom')->nullable();
           

            // Reorder & status
            $table->boolean('reorder')->default(true);
            $table->boolean('status')->default(true);

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
