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
        Schema::create('goods_receivings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_order_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('received_by')->nullable();
            $table->date('received_on')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receivings');
    }
};
