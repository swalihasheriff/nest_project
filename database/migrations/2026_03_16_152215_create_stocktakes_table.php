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
        Schema::create('stocktakes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->string('worksheet_name');
            $table->integer('type')->comment('1->Full Stocktake, 2->Selective Stocktake');
            $table->integer('status')->default(0)->comment('0->Initiated,1->Finalised');
            $table->integer('count')->nullable();
            $table->integer('variance')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocktakes');
    }
};
