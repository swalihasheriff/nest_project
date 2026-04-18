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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_receiving_id')->nullable();
            $table->unsignedBigInteger('manual_invoice_id')->nullable();

            $table->string('invoice_number')->nullable();

            $table->tinyInteger('status')
                ->default(0)
                ->comment('0 = draft, 1 = finalized');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
