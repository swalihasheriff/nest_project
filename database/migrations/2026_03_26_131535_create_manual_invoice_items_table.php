<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manual_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('manual_invoice_id');
            $table->unsignedBigInteger('product_id');

            $table->string('item_description')->nullable();
            $table->string('barcode')->nullable();

            $table->decimal('item_price', 10, 2)->default(0);
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_invoice_items');
    }
};
